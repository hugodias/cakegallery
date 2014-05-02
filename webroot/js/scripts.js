Dropzone.options.drop = {
    init: function () {
        this.on("addedfile", function (file) {

            // Create the remove button
            var removeButton = Dropzone.createElement('<button class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button>');
            var viewButton = Dropzone.createElement('<button class="btn btn-sm btn-info pull-right"><i class="fa fa-search"></i></button>');
            var setCoverButton = Dropzone.createElement('<button class="btn btn-sm btn-info pull-right"><i class="fa fa-picture-o"></i></button>');

            var base_url = jQuery("#folderinfo").data("public-folder-path");

            var name = file.name;
            var cover = ((file.cover == 'Y') ? true : false);
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


            setCoverButton.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();

                var file_id = file.id;

                // Ajax to set cover
            })


            // Listen to the click event
            removeButton.addEventListener("click", function (e) {
                // Make sure the button click doesn't submit the form:
                e.preventDefault();
                e.stopPropagation();


                var baseuri = jQuery("body").data("base-url");


                $.ajax({
                    url: baseuri + "pictures/delete/",
                    context: document.body
                }).done(function () {
                        $(this).addClass("done");
                    });

                // Remove the file preview.
                _this.removeFile(file);
                // If you want to the delete the file on the server as well,
                // you can do the AJAX request here.
            });

            // Add the button to the file preview element.
            file.previewElement.appendChild(removeButton);

            if(!cover){
                file.previewElement.appendChild(setCoverButton);
            }

        });
    }
};
Dropzone.autoDiscover = false;