<style>
/* meta box */
#voa-top-content-meta-01 p { margin-bottom: 0; }
.voa-bad-field { color: red }
.voa-bad-field input, .voa-bad-url textarea {
    background-color: crimson;
    color: white;
}
.voa-bad-url::after {
    content: "Invalid URL, please check";
}
voa-layout {
    margin-right: 1em;
    display: flex;
    flex-direction:column;
    flex-basis: 50%;
}
voa-row { display: flex; flex-direction: row; }
voa-indicator { padding-top: 1em; width: 400px; }
voa-control {
    display: flex;
    flex-direction: row;
    align-items: flex-end;
    padding-bottom:11px;
}

voa-control button {
    background-color: #ffffff;
    border: 1px solid #c0c0c0;
    border-radius: 15px;
    margin-right: 4px;
    cursor: pointer;
}

voa-control button.more-options {
    border:1px dashed black;
}

voa-control button:hover {
    background-color: #7FDBFF;
}

span.voa-draft {
    font-weight: bold;
    word-break: normal;
    white-space: nowrap;
}

voa-today { display: flex; flex-direction: row }

/* s */

show-items {
    display: flex;
}

show-items p {
    margin-right: 5em;
}

save-things {
    display: flex;
    margin-top:3em;
}
save-things button {
    cursor: pointer;
    margin-right: 1em;
}
#save-layout {
    padding: 1em;
}

/* drag and drop */

available-stories {
    width: 100%;
    min-height: 3em;
    background-color: #DDDDDD;
}

.voa-layout-story {
    border: 1px solid #c0c0c0;
    margin-bottom: 3px;
    padding: 3px;
    width: 100%;
    word-break: break-all;
}

.voa-layout-story {
    background-image: url(<?php echo get_template_directory_uri() . "/img/hint-image.png" ?>);
    background-repeat: no-repeat;
    background-size: 16px 16px;
    background-position: 0px 6px;
    text-indent: 18px;

    color: #0074D9 !important;
}
.voa-layout-story.text-heavy {
    color: #000000 !important;
    background-image: url(<?php echo get_template_directory_uri() . "/img/hint-text.png" ?>);
    box-shadow: 0 0 0px;
}

.vtcmb td {
    background-color: silver;
    box-shadow: 0 0 10px #0074D9;
}

.text-heavy-td {
    background-color: white !important;
    box-shadow: 0 0 0px !important;
}


.gu-transit { background-color: #FF851B }

table.vtcmb { width: 100% }
.vtcmb td {
    border-bottom:4px solid #0074D9;
    border-right: 4px solid #0074D9;
}
.vtcmbdd .voa-layout-story {
    border: 0;
}
.vtcmb td:last-child { border-right: 0; }
voa-row:last-child td { border-bottom: 0; }
.vtcmb td { padding:0.5em; vertical-align: top }
.vtcmbdd {}
.vtcmbdd:empty { background-color: #FFDC00; padding:1em;  }

/* calendar */
.voa-top-content-layout-nav-container { }
.voa-top-content-layout-nav { }
.voa-top-content-layout-nav th,
.voa-top-content-layout-nav td a,
.voa-top-content-layout-nav .not-a-day { padding: 0.75em; }

.voa-top-content-layout-nav .satsun { background-color: #AAAAAA }
.voa-top-content-layout-nav .has-posts a { color: #FF4136; font-weight: bold; }

.voa-top-content-layout-nav .laid-out { background-color: #7fdbff; }
.voa-top-content-layout-nav .has-posts.laid-out { background-color: #0074d9; }

.voa-top-content-layout-nav th { background-color: #001f3f; color: white }
.voa-top-content-layout-nav a { display: block; }
.voa-top-content-layout-nav .laid-out a { color: black }
.voa-top-content-layout-nav caption { font-weight: bold; }

.voa-top-content-layout-nav-container p {
    line-height: 1em;
    margin-bottom: 0.3em;
    margin-top: 0;
}
.voa-top-layout-legend p { line-height: 1.5em }

/* dragula */
.gu-mirror {
  position: fixed !important;
  margin: 0 !important;
  z-index: 9999 !important;
  opacity: 0.8;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)";
  filter: alpha(opacity=80);
}
.gu-hide {
  display: none !important;
}
.gu-unselectable {
  -webkit-user-select: none !important;
  -moz-user-select: none !important;
  -ms-user-select: none !important;
  user-select: none !important;
}
.gu-transit {
  opacity: 0.2;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)";
  filter: alpha(opacity=20);
}

/* action verification buttons */
.verify-action { width: 200px; }
.action-verified { color: #FF4136; font-weight: bold }
.action-not-yet { cursor: not-allowed; }

</style>
