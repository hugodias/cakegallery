Dropzone.options.drop = {
    init: function () {
        this.on("addedfile", function (file) {



            // Create the remove button
            var removeButton = Dropzone.createElement('<button class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button>');
            var viewButton = Dropzone.createElement('<button class="btn btn-sm btn-info pull-right"><i class="fa fa-search"></i></button>');

            var base_url = jQuery("#folderinfo").data("public-folder-path");

            var name = file.name;
            var cover = ((file.cover == 'Y') ? true : false);
            var file_id = file.id;
            var path = base_url + name;
            var th_path = base_url + "TH/" + name;

            // Capture the Dropzone instance as closure.
            var _this = this;


            viewButton.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();

                jQuery("#pictureName").html(name);
                jQuery(".img-preview-full").attr('src', path);
                jQuery("#modalViewPicture").modal('show');
            });


            // Removing file
            removeButton.addEventListener("click", function (e) {
                // Make sure the button click doesn't submit the form:
                e.preventDefault();
                e.stopPropagation();

                var resp = confirm("Are you sure?");

                if (resp) {
                    var baseuri = jQuery("body").data("plugin-base-url");

                    $.ajax({
                        url: baseuri + "/pictures/delete/" + file_id,
                        context: document.body
                    }).done(function () {
                            // Remove the file preview.
                            _this.removeFile(file);
                        });
                }


            });

            if(file.id){
                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);
            }
        });
    }
};
Dropzone.autoDiscover = false;