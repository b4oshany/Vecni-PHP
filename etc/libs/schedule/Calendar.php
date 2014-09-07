<?php
namespace libs\schedule;
require_once ".autoload.php";
use configs\Vecni;
use libs\mongodb\Model;
use libs\user\User;
use libs\location\Location;
use libs\schedule\Event;
class Calendar extends Model{

    # Basic calendars information,
    public $title;                                  // Title of the calendar.
    public $description;                            // Description of the calendar.
    public $creator;                                // Creator of the calendar.
    public static $collection = "calendar";         // name of collection to be used

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
        self::$mongodb->calendar->createIndex(
            array("title"=>1,
                  "creator"=>1
                 ),
            array("unique"=>1)
        );
    }


    /**
    * Create a new calendar
    * @param mixed $title - calendar title
    * @param mixed $description - calendar description
    * @return Event - event obejct with the start time, title and description attached to it
    */
    public static function create($title, $description=""){
        # create a new event object and set the values of the title, description and start time
        $new_cal = new self();
        $new_cal->title = $title;
        $new_cal->description = $description;
        $date = new \DateTime('NOW');
        $new_cal->date_created = $date;
        $new_cal->creator = User::get_current_user_db()->get_MongoId();
        $new_cal->save();
        return $new_cal;
    }

    /**
    * Set the duration of the event
    * @param mixed $duration duration of the event which will determine the end time
    * @return Event - new created event
    */
    public function create_event($title, \DateTime $start_time, $description){
        $new_event = Event::create($title, $start_time, $description);
        $new_event->default_calendar = $this->get_MongoId();
        $new_event->subscribe_calendar[0] = $this->get_MongoId();
        $new_event->save();
        return $new_event;
    }

    public function get_month_events(\DateTime $from = null, \DateTime $to = null){
        if(!isset($from)){
            $from = new \DateTime('NOW');
        }
        $from->sub(new \DateInterval('P1M'));
        if(!isset($to)){
            $to = clone $from;
            $to->add(new \DateInterval('P3M'));
        }
        return Event::find(array(
            '$or'=>array(
                array(
                    "subscribe_calendar"=>array(
                        '$all'=>array($this->get_MongoId())
                    )
                ),
                array("default_calendar"=>$this->get_MongoId())
            ),
            "start_time.date" =>array(
                '$gte'=>$from->format("Y-m-d H:i:s"),
                '$lte'=>$to->format("Y-m-d H:i:s")
            )
        ));
    }

    public function get_calendar_events(\DateTime $from = null, \DateTime $to = null){
        $events = $this->get_month_events($from, $to);
        $calendar_format = array();
        foreach($events as $event){
            array_push($calendar_format, $event->get_calendar_format());
        }
        return $calendar_format;
    }

    public function get_event($event_id){
        $event_object_id = self::create_MongoId($event_id);
        $event = Event::find_one(array(
            "_id"=>$event_object_id,
            "subscribe_calendar"=>array(
                '$all'=>array($this->get_MongoId())
            )
        ));
        if(isset($event)){
            return $event;
        }
        return null;
    }

    /**
    * Calculate the ending time of the event based on the duration and start time
    * @return date end time of the event
    */
    public function get_end_time(){
        $date = $this->start_time;
        date_add($date, date_interval_create_from_date_string($this->duration));
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
}
Calendar::setUp();
Calendar::collectionSettings();
?>
