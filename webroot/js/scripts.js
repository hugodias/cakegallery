var Album = {
    init: function (settings) {
        Album.config = {
            id: $('#albumInfo').data('album-id'),
            editable: {
                postUrl: $('body').data('plugin-base-url') + '/pictures/caption'
            },
            dropzone: {
                uploadContainer: $('#uploadContainer'),
                canvas: $('#canvasup'),
                uploadUrl: $('#albumInfo').data('post-url')
            },
            items: $("#sortable"),
            container: $('#container-pictures'),
            trashIconEl: '.remove-picture',
            baseUrl: $('body').data('plugin-base-url')
        };

        Album.config.fetchUrl = Album.config.baseUrl + '/pictures/index/' + Album.config.id;

        // Allow overriding the default config
        $.extend(Album.config, settings);

        Album.setup();
    },


    setup: function () {
        // Bind some jquery plugins
        Album.jQueryBinds();

        // Customize toasrt plugin
        Album.configureToastr();

        // Configure dropzone for file uploading
        Album.configureDropzone();
        // Retrieve all pictures from current album
        Album.fetch();

        Album.configureEditable();

        Album.configureDelete();

        Album.configureEffects();
    },

    jQueryBinds: function () {
        $('.popovertrigger').popover({
            html: true
        });

        $('.panel-heading.options, .close-config, .open-config').bind('click', function () {
            $('.panel.options').slideToggle(300);
        });

        $('.swipebox').swipebox();
    },

    configureEffects: function () {
        Album.config.items
            .sortable({
                opacity: 0.5,
                update: function (event, ui) {
                    Album.saveOrder();
                }
            }).disableSelection();


        $(document).on('mouseover', '.th-pictures-container', function (e) {
            $(this).children('div .image-actions').show();
            e.stopPropagation();
        });

        $(document).on('mouseout', '.th-pictures-container', function () {
            $(this).children('div .image-actions').hide();
        });

    },

    fetch: function () {
        $.getJSON(Album.config.fetchUrl, function (response) {

            var pictures = response.Picture;

            for (var i = 0; i < pictures.length; i++) {
                Album.renderPicture(
                    pictures[i].id,
                    pictures[i].styles.medium,
                    pictures[i].caption,
                    pictures[i].styles.large
                );
            }
        });
    },
    configureEditable: function () {
        $.fn.editableform.buttons = '<button type="submit" class="editable-submit btn btn-sm btn-primary"><i class="fa fa-check"></i></button>' +
        '<button type="button" class="editable-cancel btn btn-sm btn-danger"><i class="fa fa-times"></i></button>';

        $('.caption .text').each(function () {
            $(this).editable({
                type: 'textarea',
                pk: $(this).data('id'),
                url: Album.config.editable.postUrl,
                emptytext: 'No caption',
                title: 'Image caption',
                success: function () {
                    toastr.success('Caption changed.');
                }
            });
        });

    },

    configureDropzone: function () {
        var Drop = new Dropzone(document.body, {
            previewsContainer: "#previews",
            clickable: '.uploadButton',
            url: Album.config.dropzone.uploadUrl
        });

        Drop.on("sending", function (file, xhr, formData) {
            var album_id = $('#AlbumId').val();
            formData.append("album_id", album_id);

            document.querySelector(".progress-bar.progress-bar-success").style.opacity = "1";

            Album.config.dropzone.uploadContainer.slideDown(400);

            Album.config.dropzone.canvas.hide();
        });


        Drop.on("dragenter", function () {
            // Todo: Fix canvas behavior
            // Album.config.dropzone.canvas.show();
        });

        Drop.on("totaluploadprogress", function (progress) {
            document.querySelector(".progress-bar.progress-bar-success").style.width = progress + "%";
        });

        Drop.on("success", function (r, response) {
            Album.renderPicture(
                response.picture.Picture.id,
                response.picture.Picture.styles.medium, null, response.picture.Picture.styles.large);

            Album.hideEmptyContainer();
        });

        Drop.on("queuecomplete", function (progress) {
            document.querySelector(".progress-bar.progress-bar-success").style.opacity = "0";

            toastr.success('Upload complete.');

            window.setTimeout(function () {
                Album.config.dropzone.uploadContainer.slideUp(500);
                $('.dz-preview').delay(1000).remove();
            }, 2000);
        });
    },

    configureToastr: function () {
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
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

    configureDelete: function () {
        $(document).on('click', Album.config.trashIconEl, Album.removePicture);
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

        var $box = _this.closest('li');

        var file_id = $(this).data('file-id');

        swal({
            title: __("Are you sure?"),
            text: __("You will not be able to recover this picture!"),
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: __("Yes, delete it!"),
            closeOnConfirm: false
        }, function () {
            $.ajax({
                url: Album.config.baseUrl + "/pictures/delete/" + file_id,
                context: document.body
            }).done(function () {
                swal(__("Deleted!"), __("Your picture has been deleted."), "success");
                $box.hide(500);
            });
        });
    },

    renderPicture: function (id, url, caption, large) {
        var template = $('#pictureBoxTemplate').html();
        Mustache.parse(template);
        var rendered = Mustache.render(template, {id: id, url: url, caption: caption, large: large});
        $('#sortable').append(rendered);

        Album.configureEditable();
    },


    hideEmptyContainer: function () {
        if ($('.container-empty').length)
            $('.container-empty').remove();
    }
};


$(document).ready(Album.init);

/**
 * A dummy gettext translation function, so this file has no dependency on
 * a particular js implementation of gettext
 */
if (typeof __ == 'undefined') {
    var __ = function (msg) {
        return (typeof App != 'undefined' && typeof App.i18n != 'undefined' ? App.i18n.gettext(msg) : msg);
    };
}
