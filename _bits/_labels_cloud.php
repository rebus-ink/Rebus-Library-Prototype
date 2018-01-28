<?php
if (!isset($label_cloud_height)) {
  $label_cloud_height = "500px";
}
?>
<div class="labelcloud-container">
  <div class="labelcloud-header"><?=$label_cloud_header; ?></div>
  <div class="labelcloud-inner">
  <div id="<?=$label_cloud_div_id; ?>" class="labelcloud" style="width:100%;height:<?=$label_cloud_height;?>"></div>
  </div>
  <script>
    var tags = [<?php
    // Array_sort_by_column -> functions.php
    array_sort_by_column($label_index, 'label', SORT_ASC);
    //print_r($label_index);
    foreach ($label_index as $lid => $linfo) {
      if ($label_uri_type == "id") {
        $label_uri_id = $linfo['id'];
      } else {
        $label_uri_id = $linfo['label'];
      }
      echo "{text: '". addslashes(urldecode($linfo['label'])) ."', weight: ". $linfo['count'] .", link: '".$label_uri_base. $label_uri_id ."'},";
    }
    ?>];
    $('#<?=$label_cloud_div_id;?>').jQCloud(tags, {
      autoResize: true,
      colors: ['#0E0E0E','#1C1C1C','#2A2A2A','#383838','#464646','#535353','#616161','#6F6F6F','#7D7D7D','#8B8B8B','#999999']
    });
  </script>
</div>