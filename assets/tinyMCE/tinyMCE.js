/* Import TinyMCE */
import tinymce from "tinymce";

/* Default icons are required for TinyMCE 5.3 or above */
import "tinymce/icons/default";

import "tinymce/models/dom/model";
import "tinymce/skins/ui/oxide/skin.min.css";
import "tinymce/skins/ui/oxide/content.min.css";
import "tinymce/skins/content/default/content.min.css";

/* A theme is also required */
import "tinymce/themes/silver";

/* Import plugins */
import "tinymce/plugins/advlist";
import "tinymce/plugins/code";
import "tinymce/plugins/emoticons";
import "tinymce/plugins/emoticons/js/emojis";
import "tinymce/plugins/link";
import "tinymce/plugins/lists";
import "tinymce/plugins/table";

/* Import premium plugins */
/* NOTE: Download separately and add these to /src/plugins */
/* import './plugins/checklist/plugin'; */
/* import './plugins/powerpaste/plugin'; */
/* import './plugins/powerpaste/js/wordimport'; */

// require("../../node_modules/tinymce/skins/ui/tinymce-5/skin.min.css");
// require("../../node_modules/tinymce/skins/ui/tinymce-5/content.min.css");

console.log("SCRIPT CHARGE");

tinymce.init({
    selector: ".tinymce",
    plugins: "advlist code emoticons link lists table",
    toolbar: "bold italic | bullist numlist | link emoticons",
    // skin: false,
    // content_css: "/public/build/skins/content/default/content.min.css",
    // content_css: false,
    // suffix: ".min",
    // content_style: contentUiCss.toString() + "\n" + contentCss.toString(),
});
