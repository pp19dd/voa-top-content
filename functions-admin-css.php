<style>
voa-layout { margin-right: 1em; display: flex; flex-direction:column }
voa-row { display: flex; flex-direction: row; padding-bottom:1em }
voa-indicator { padding-top: 1em }
voa-control { padding: 1em; display: flex; flex-direction: column }
voa-control button { }

voa-today { display: flex; flex-direction: row }

/* drag and drop */
.voa-layout-story {
    border: 1px solid #c0c0c0;
    margin-bottom: 3px;
    padding: 3px;
    width: 100%;
}

.vtcmb td { padding:0.5em; vertical-align: top }
.vtcmbdd { padding: 1em; background-color: yellow }

/* calendar */
.voa-top-content-layout-nav-container { float: right }
.voa-top-content-layout-nav { }
.voa-top-content-layout-nav th,
.voa-top-content-layout-nav td a,
.voa-top-content-layout-nav .not-a-day { padding: 0.75em; }

.voa-top-content-layout-nav .satsun { background-color: silver }
.voa-top-content-layout-nav .laid-out { background-color: #3D9970; }
.voa-top-content-layout-nav th { background-color: #001f3f; color: white }
.voa-top-content-layout-nav a { display: block; }
.voa-top-content-layout-nav .laid-out a { color: black }
.voa-top-content-layout-nav caption { font-weight: bold; }

.voa-top-content-layout-nav-container p {
    line-height: 1em;
    margin-bottom: 0.3em;
    margin-top: 0;
}

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

</style>
