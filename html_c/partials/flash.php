<?php
/*put this at the bottom of the page so any templates
 populate the flash variable and then display at the proper timing*/
?>
<div class="container" id="flash">
    <?php $result = getMessages(); ?>
    <?php if ($result): ?>
        <?php foreach ($result as $row): ?>
            <div class="row bg-secondary justify-content-center">
                <p style="margin-left: auto; margin-right:auto;"><?php  ?></p>
            </div>
	<?php foreach ($row as $value): ?>
            <div class="row bg-secondary justify-content-center">
                <p style="margin-left: auto; margin-right:auto;"><?php echo $value; ?></p>
            </div>
        <?php endforeach; ?>
	<?php endforeach; ?>

    <?php endif; ?>
</div>
<script>
    //used to pretend the flash messages are below the first nav element
    function moveMeUp(ele) {
        let target = document.getElementsByTagName("nav")[0];
        if (target) {
            target.after(ele);
        }
    }
    moveMeUp(document.getElementById("flash"));
</script>
