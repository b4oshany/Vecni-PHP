/**
* Represent a Drag and Drop interface for file uploader.
* @class FileUploader
* @property {int} rowCount              - num of bits.
* @property {HTMLElement} dropper       - Drag and Drop container.
* @property {string} formData           - form data.
* @property {double} status             - percentage completed.
* @property {string[]} response         - response from the server.
* @proeprty {string} url                - url of the server.
*/
function FileUploader(options, url){
    this.rowCount = 0;
    this.status = null;
    this.response = [];
    this.url = url;
    this.files = [];
    this.options = {
        "target": undefined,
        "dragArea": undefined
    };

    this.enable_drag_drop = function(){
        var current_object = this;
        this.dropper.on('dragenter', function (e){
            e.stopPropagation();
            e.preventDefault();
            $(this).css('border', '2px solid #0B85A1');
        });
        this.dropper.on('dragover', function (e){
             e.stopPropagation();
             e.preventDefault();
        });
        this.dropper.on('drop', function (e){
             $(this).css('border', '2px dotted #0B85A1');
             e.preventDefault();
             var files = e.originalEvent.dataTransfer.files;

             //We need to send dropped files to Server
             current_object.handleFileUpload(files);
        });
    }

    this.init = function(options){
        var dragArea = options["dragArea"];
        var obj = this;
        if(dragArea != undefined){
            this.dropper = $(dragArea);
            if(this.dropper != undefined)
                this.enable_drag_drop();
        }
        if(options["target"] != undefined){
            $(options["target"]).on('change', function(e){
                obj.handleTarget(e);
            });
        }
        this.options = options;
    }

    this.handleTarget = function(e){
        var files = e.target.files || e.originalEvent.dataTransfer.files;
        this.handleFileUpload(files);
    }

    this.handleFileUpload = function(files){
        var formData = new FormData();
        var status = new this.createStatusbar(this);
        $.each(files, function(key, file){
            formData.append("files", file);
            status.setFileNameSize(file.name, file.size);
        });
        this.sendFileToServer(this.url, formData, status);
    }

    this.sendFileToServer = function(url, formData, status){
        var current_object = this;
        var uploadURL = url; //Upload URL
        var extraData ={}; //Extra Data.
        var jqXHR=$.ajax({
                xhr: function() {
                var xhrobj = $.ajaxSettings.xhr();
                if (xhrobj.upload) {
                        xhrobj.upload.addEventListener('progress', function(event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            //Set progress
                            status.setProgress(percent);
                        }, false);
                    }
                return xhrobj;
            },
        url: uploadURL,
        type: "POST",
        contentType:false,
        processData: false,
            cache: false,
            data: formData,
            success: function(data){
                status.setProgress(100);
                current_object.response.push(data);
                $("#status1").append("File upload Done<br>");
            }
        });
        status.setAbort(jqXHR);
    }

    this.createStatusbar = function(obj){
        this.response = [];
         obj.rowCount++;
         var row="odd";
         if(obj.rowCount %2 ==0) row ="even";
         this.statusbar = $("<div class='statusbar "+row+"'></div>");
         this.filename = $("<div class='filename'></div>").appendTo(this.statusbar);
         this.size = $("<div class='filesize'></div>").appendTo(this.statusbar);
         this.progressBar = $("<div class='progressBar'><div></div></div>").appendTo(this.statusbar);
         this.abort = $("<div class='abort'>Abort</div>").appendTo(this.statusbar);
         obj.dropper.after(this.statusbar);

        this.setFileNameSize = function(name,size)
        {
            var sizeStr="";
            var sizeKB = size/1024;
            if(parseInt(sizeKB) > 1024)
            {
                var sizeMB = sizeKB/1024;
                sizeStr = sizeMB.toFixed(2)+" MB";
            }
            else
            {
                sizeStr = sizeKB.toFixed(2)+" KB";
            }

            this.filename.html(name);
            this.size.html(sizeStr);
        }
        this.setProgress = function(progress)
        {
            var progressBarWidth =progress*this.progressBar.width()/ 100;
            this.progressBar.find('div').animate({ width: progressBarWidth }, 10).html(progress + "% ");
            if(parseInt(progress) >= 100)
            {
                this.abort.hide();
            }
        }
        this.setAbort = function(jqxhr)
        {
            var sb = this.statusbar;
            this.abort.click(function()
            {
                jqxhr.abort();
                sb.hide();
            });
        }
    }

    this.init(options);
}
