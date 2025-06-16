<?php

//$bclass='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only';
$bclass = '';
$style = 'style="width:75px;height:75px;font-size:150%;"';

?>

<script type="text/javascript">
	$(function() {
		$("button").button();
		if ("<?php echo $setnumber ?>" != "0") {
			$("#view_date").html("<?php echo $setnumber ?>");
		} else {
			$("#view_date").html("");
		}
	});

	function btn_click(str) {
		if (str == "クリア") {
			$("#view_date").html("");
		} else if (str == "←") {
			var view_date = $("#view_date").html();
			$("#view_date").html(view_date.substring(0, view_date.length - 1));
		} else if (str == "←←") {
			var view_date = $("#view_date").html();
			$("#view_date").html(view_date.substring(0, view_date.length - 2));
		} else {
			$("#view_date").append(str);
		}
	}
</script>

<div style="text-align: center; margin: auto;" id="datetime_entry">
	<div style="width:100%; font-size:150%;">
		<div style="border: 4px #ccc double;width:95%;height:66px;font-size:230%;padding: 0px;background-color:#000;color:#fff;  margin: auto;" id="view_date">
		</div>
		<div style="clear:both; height: 10px"></div>
		<div style="display:block;">
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="7">7</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="8">8</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="9">9</button>
			<!-- <button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="-">-</button> -->
		</div>
		<div style="clear:both; height: 8px"></div>
		<div style="display:block;">
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="4">4</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="5">5</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="6">6</button>
			<!-- <button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="+">+</button>-->
		</div>
		<div style="clear:both; height: 8px"></div>
		<div style="display:block;">
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="1">1</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="2">2</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="3">3</button>
			<!-- <button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="X">X</button>-->
		</div>
		<div style="clear:both; height: 8px"></div>
		<div style="display:block;">
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="0">0</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="00">00</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value=".">.</button>
			<!-- <button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="En">/</button>-->
		</div>
		<div style="clear:both; height: 8px"></div>
		<div style="display:block;">
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="クリア">クリア</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="←">←</button>
			<button type="button" style="width: 30%; height:60px;" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="←←">←←</button>
			<!-- <button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="En">/</button>-->
		</div>
		<div style="clear:both;"></div>
	</div>

	<div style="clear:both;"></div>

</div>
