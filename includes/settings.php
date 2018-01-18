<?php
function out_of_date_content_dashboard_display() {
    $outList = new Out_Of_Date_List_Table();
    ?>
	<div class="wrap">
	    <form method="post">
		<?php
		    $outList->prepare_items();
		    $outList->display();
		?>
	    </form>
	</div>
    <?php
}
?>