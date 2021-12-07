<?php

Echo"<link href=\"css/style.css\" rel=\"stylesheet\" type=\"text/css\" />
<link href=\"css/sidenav.css\" rel=\"stylesheet\" type=\"text/css\" />
<link rel=\"stylesheet\" href=\"css/flatpickr.min.css\">
<script src=\"js/flatpickr\"></script>
<script src=\"js/jquery-3.4.1.js\"></script>
<link href=\"css/select2.min.css\" rel=\"stylesheet\" />
<script src=\"js/select2.min.js\"></script>
<link href=\"css/custom.css\" rel=\"stylesheet\" type=\"text/css\" />
<script src=\"js/jquery.tablesorter.js\"></script>

<script>
//Reload page on browser back
if(performance.navigation.type == 2){
location.reload(true);
}
</script>
<script>
//Table sorting
$(function() {
$(\".sortable\").tablesorter({
});
});
</script>";

?>