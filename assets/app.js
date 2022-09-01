/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";

// start the Stimulus application
import "./bootstrap";
import "bootstrap";

import tinymce from "tinymce";

import "tinymce/icons/default";
import "tinymce/models/dom/model";
// A theme is also required
import "tinymce/themes/silver/theme";

import "tinymce/skins/ui/oxide/skin.min.css";
// Any plugins you want to use has to be imported
// import "tinymce/plugins/paste";
// import "tinymce/plugins/link";

/* Import plugins */
import "tinymce/plugins/advlist";
import "tinymce/plugins/code";
import "tinymce/plugins/emoticons";
import "tinymce/plugins/emoticons/js/emojis";
import "tinymce/plugins/link";
import "tinymce/plugins/lists";
import "tinymce/plugins/table";
import "tinymce/plugins/image";
import "tinymce/plugins/media";
import "tinymce/plugins/visualblocks";
import "tinymce/plugins/template";
import "tinymce/plugins/anchor";
import "tinymce/plugins/autolink";

import contentCss from "tinymce/skins/content/default/content.min.css";
import contentUiCss from "tinymce/skins/ui/oxide/content.min.css";

tinymce.PluginManager.add("addicon", function (editor, url) {
    // add plugin code here

    return {
        getMetadata: function () {
            return {
                name: "Custom plugin",
                url: "https://example.com/docs/customplugin",
            };
        },
    };
});

// Initialize the app
tinymce.init({
    selector: ".tinymce",
    skin: false,
    content_css: false,
    content_style: [contentCss, contentUiCss].join("\n"),
    plugins:
        "advlist code emoticons link lists table image media visualblocks template anchor autolink",
    min_height: 500,
    toolbar:
        "undo redo | formatselect | " +
        "bold italic backcolor blocks | alignleft aligncenter " +
        "alignright alignjustify | bullist numlist outdent indent | addicon image media visualblocks template " +
        "removeformat | code | help ",

    visualblocks_default_state: true,
    templates: [
        {
            title: "Organigramme widget",
            description: "Widget pour une personne de l'organigramme.",
            content:
                "<div class='people'><div class='people__infos'><p>Prénom</p><h4>Nom</h4><p>Rôle</p></div><img src='https://www.residencescogir.com/DATA/NOUVELLE/79.jpg'/></div>",
        },
        {
            title: "Flex box horizontale 2 blocs",
            description: "Dispose les éléments horizontalement",
            content:
                "<div class='d-flex flex-wrap flex-lg-nowrap justify-content-around w-100 gap-4'><div class=' d-flex justify-content-center text-lg-end'><p>Lorem ipsum dolor sit amet. Est enim placeat aut praesentium officia sit voluptas commodi vel tempore placeat. Et modi dolorum in tempora est totam adipisci et debitis culpa sit nemo quos est dolorem voluptatum hic voluptatem quasi.Aut quaerat atque et quas quibusdam ut atque rerum. Ab veritatis porro qui fugit odit et deserunt molestias ad rerum officiis et distinctio temporibus. Et distinctio consequatur est culpa molestiae ex nobis exercitationem qui aliquid voluptates et nihil excepturi. Ut illum doloribus rem facere nemo et officia vitae!Ut internos provident et vero fugiat et enim laudantium et nobis Quis est autem dignissimos? Sit architecto beatae sed consequatur laudantium sit voluptatem molestiae est debitis rerum nam mollitia veniam sit soluta impedit. Aut laudantium voluptatem aut eius magnam est consequatur reiciendis aut labore animi sit rerum vero.</p></div><div class='d-flex justify-content-center align-items-center'><p>Lorem ipsum dolor sit amet. Est enim placeat aut praesentium officia sit voluptas commodi vel tempore placeat. Et modi dolorum in tempora est totam adipisci et debitis culpa sit nemo quos est dolorem voluptatum hic voluptatem quasi.Aut quaerat atque et quas quibusdam ut atque rerum. Ab veritatis porro qui fugit odit et deserunt molestias ad rerum officiis et distinctio temporibus. Et distinctio consequatur est culpa molestiae ex nobis exercitationem qui aliquid voluptates et nihil excepturi. Ut illum doloribus rem facere nemo et officia vitae!Ut internos provident et vero fugiat et enim laudantium et nobis Quis est autem dignissimos? Sit architecto beatae sed consequatur laudantium sit voluptatem molestiae est debitis rerum nam mollitia veniam sit soluta impedit. Aut laudantium voluptatem aut eius magnam est consequatur reiciendis aut labore animi sit rerum vero.</p></div></div>",
        },
    ],

    automatic_uploads: false,
    images_upload_url: "",
    file_picker_types: "image",
    file_picker_callback: function (cb, value, meta) {
        var input = document.createElement("input");
        input.setAttribute("type", "file");
        input.setAttribute("accept", "image/*");

        input.onchange = function () {
            var file = this.files[0];

            var reader = new FileReader();
            reader.onload = function () {
                /*
              Note: Now we need to register the blob in TinyMCEs image blob
              registry. In the next release this part hopefully won't be
              necessary, as we are looking to handle it internally.
            */
                var id = "blobid" + new Date().getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(",")[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
            };
            reader.readAsDataURL(file);
        };

        input.click();
    },
});
