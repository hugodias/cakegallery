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

                var resp = confirm(__("Are you sure?"));

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

            if (file.id) {
                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);
            }
        });
    }
};
Dropzone.autoDiscover = false;


var Album = {
    init: function (settings) {
        Album.config = {
            items: $("#sortable"),
            container: $('#container-pictures'),
            trashIconEl: $('.remove-picture'),
            baseUrl: $('body').data('plugin-base-url')
        };

        // Allow overriding the default config
        $.extend(Album.config, settings);

        Album.setup();

        // Customize toasrt plugin
        Album.configureToastr();
    },

    configureToastr: function () {
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-left",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    },

    setup: function () {
        Album.config.items
            .sortable({
                opacity: 0.5,
                update: function (event, ui) {
                    Album.saveOrder();
                }
            }).disableSelection();

        Album.config.trashIconEl.on('click', Album.removePicture)
    },


    saveOrder: function () {
        var sorted = Album.config.items.sortable("toArray").join(",");

        $.post(Album.config.baseUrl + '/pictures/sort', {
            order: sorted
        }, function (response) {
            toastr.success('Order saved!');
        });
    },

    removePicture: function (e) {
        e.preventDefault();
        e.stopPropagation();

        var _this = $(this);

        var $box = _this.parent().parent().parent();

        var file_id = $(this).data('file-id');

        var resp = confirm(__("Are you sure?"));

        if (resp) {
            $.ajax({
                url: Album.config.baseUrl + "/pictures/delete/" + file_id,
                context: document.body
            }).done(function () {
                toastr.success('Picture removed!');
            });
        }

    }

};


$(document).ready(Album.init);


$(function () {
    $('.confirm-delete').on('click', function (e) {
        var link = this;

        e.preventDefault();

        var resp = confirm(__("Are you sure?"));

        if (resp) {
            window.location = link.href;
        }
    })

    $('.modal-upload').on('hidden.bs.modal', function (e) {
        window.location.reload();
    })


    $('.th-pictures-container')
        .mouseover(function (e) {
            $(this).children('.icons-manage-image').show();
            e.stopPropagation();
        })
        .mouseout(function (e) {
            $(this).children('.icons-manage-image').hide();
        })


    $('.popovertrigger').popover({
        html: true
    });

    $('.panel-heading.options, .close-config, .open-config').bind('click', function () {
        $('.panel.options').slideToggle(300);
    })
})

/**
 * A dummy gettext translation function, so this file has no dependency on
 * a particular js implementation of gettext
 */
if (typeof __ == 'undefined') {
    var __ = function (msg) {
        return (typeof App.i18n != 'undefined' ? App.i18n.gettext(msg) : msg);
    };
}
