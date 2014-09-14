<?php
namespace libs\schedule;
require_once ".autoload.php";
use configs\Vecni;
use libs\mongodb\Model;
use libs\mongodb\MongoIdRef;
use libs\user\User;
use libs\location\Location;
use libs\schedule\Calendar;
class Event extends Model{
    public static $collection = "event";

    # Basic event information
    public $title;              // title of the event
    public $venue;              // location of the event
    public $description;        // description of the event


    public $start_time;         // start time of the event
    public $duration;           // duration of the event which will determine the end time

    public $recurrence_type;    // type of recurrency event, such as hourly, daily, weekly, etc
    public $end_date;           // ending date for the recurrent event should stop

    public $creator;            // creator of the event
    public $attendees;          // attendees of the event
    public $default_calendar;           // main calendar
    public $subscribe_calendar;

    /**
    * __callstatic is triggered when invoking inaccessible methods in an static context
    * This method will initiate the mongodb connection and select the desired database
    * In addition, it will initiate the collection model for the user data.
    */
    public static function setUp(){
        parent::setUp();
        $collection = self::$collection;
        self::$model = self::$mongodb->$collection;
    }

    /**
    * Set the collection settings for monogodb
    * such as the unique attributes in the collection
    */
    public static function collectionSettings(){
        self::$mongodb->user->createIndex(
            array("title"=>1,
                  "calendar"=>1,
                  "creator"=>1
                 ),
            array("unique"=>1)
        );
    }

    public function enforce_constraints(){
        $this->start_time = self::cast("DateTime", $this->start_time);
        $this->duration = self::cast("DateInterval", $this->duration);
    }

    public function __construct(){
        $this->duration = new \DateInterval("PT1H");
        $this->subscribe_calendar = array();
    }

    /**
    * Create a new event
    * @param mixed $title - event title
    * @param mixed $description - event description
    * @param date $start_time - the time which the event will start
    * @return Event - event obejct with the start time, title and description attached to it
    */
    public static function create($title, \DateTime $start_time, $description=""){
        # create a new event object and set the values of the title, description and start time
        $new_event = new self();
        $new_event->title = $title;
        $new_event->description = $description;
        $new_event->start_time = $start_time;
        $new_event->creator = User::get_current_user_db()->get_MongoId();
        return $new_event;
    }

    /**
    * Add a calendar that subscribes to this event.
    * @param MongoId $calendar_ref - Calendar document ref.
    * @return bool - true if the update is successful else false.
    */
    public function add_calendar_subscription(\MongoId $calendar_id){
        if($this->update(array(
            '$addToSet'=>array('subscribe_calendar'=>$calendar_id)
        ))){
            return true;
        }
        return false;
    }

    /**
    * Set the duration of the event, which is a DateInterval that will be used
    * as the DateTime add method parameter
    * @param int $hours - number of hours for the event duration
    * @param int $mins - number of minutes for the event duration
    * @param int $secs - number of seconds for the event duration
    */
    public function set_duration_time($hours = 0, $mins = 0, $secs = 0){
        $this->duration->h = $hours; // set duration hours
        $this->duration->i = $mins;  // set duration minutes
        $this->duration->s = $secs; // set duration seconds
    }

    /**
    * Calculate the ending time of the event based on the duration and start time
    * @return DateTime - end time of the event
    */
    public function get_end_time(){
        $date = $this->start_time;
        $date->add($this->duration);
        return $date;
    }

    /**
    * Set the location of the event
    * @param Location|string $venue - set the location of the venue, if Location, then there will be
    * an associated location object with the event address and cordinates on map, else it's a stirng
    * that will be displayed
    */
    public function change_venue($venue = " "){
        if($venue instanceof Location || $venue instanceof string){
            $this->venue = $venue;
        }else{
            throw new \Exception("Incorrect type, venue must be a Location object or String");
        }
    }

    /**
    * Get a basic event summary for the calendar which includes, the title, description,
    * start and end time of the event as well as the event creator name and id
    * @return array - event data
    */
    public function get_calendar_format(){
        $event_data = array();
        $event_data["start"] = $this->start_time->format("c");
        $end_time = $this->get_end_time();
        $event_data["end"] = $end_time->format("c");
        $event_data["title"] = $this->title;
        $event_data["description"] = $this->description;
        $event_data["id"] = $this->id;
        $user = User::find_one(
            array("_id"=>$this->creator),
            array("first_name", "last_namee")
        );
        $event_data["creator"] = array(
            "name"=>"$user->first_name $user->last_name",
            "id"=>"$user->id"
        );
        return $event_data;
    }
}
Event::setUp();
Event::collectionSettings();
?>
