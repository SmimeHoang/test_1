<?php

//$bclass='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only';
$bclass = '';
$style = 'style="width:75px;height:75px;font-size:150%;"';

?>

<script type="text/javascript">
	$(function() {
		datetimeset();
		$("button").button();
	});

	function btn_click(str) {
		if (str == "C") {
			$("#view_date").html("");
		} else if (str == "En") {
			alert("決定" + $("#view_date").text());
			$("#view_date").html("");
		} else {
			$("#view_date").append(str);
		}
	}

	function btn_mounth() {
		var mounth = $.trim($("#view_date").text());
		if (mounth < 13) {
			$("#view_date").html("");
			$("#mounth").text(mounth);
		}
	}

	function btn_day() {
		var day = $.trim($("#view_date").text());
		if (day < 32) {
			$("#view_date").html("");
			$("#day").text(day);
		}
	}

	function btn_houre() {
		var houre = $.trim($("#view_date").text());
		if (houre < 24) {
			$("#view_date").html("");
			$("#houre").text(houre);
		}
	}

	function btn_minits() {
		var minits = $.trim($("#view_date").text());
		if (minits < 60) {
			$("#view_date").html("");
			$("#minits").text(minits);
		}
	}

	function btn_year(str) {
		var year = $.trim($("#view_date").text());
		$("#view_date").html("");
		$("#year").html(year);
	}

	function btn_clear(str) {
		var minits = $("#view_date").text();
		$("#view_date").html("");
		$("#mounth").html("");
		$("#day").html("");
		$("#houre").html("");
		$("#minits").html("");
	}

	function btn_entry(id) {
		var year = $("#year").text();
		var mounth = ("0" + $("#mounth").text()).slice(-2);
		var day = ("0" + $("#day").text()).slice(-2);
		var houre = ("0" + $("#houre").text()).slice(-2);
		var minits = ("0" + $("#minits").text()).slice(-2);
		var out_date = year + "/" + mounth + "/" + day;
		var out_time = houre + ":" + minits;
		if (mounth || day || (houre && minits)) {
			$('#' + id).val(out_time)
			if (id == '終了日時') {
				$('#end_date').val(out_date)
			}
		} else {
			$('#' + id).val("")
			if (id == '終了日時') {
				$('#end_date').val("")
			}
		}
	}

	function btn_getvalue() {
		var year = $("#year").text();
		var mounth = ("0" + $("#mounth").text()).slice(-2);
		var day = ("0" + $("#day").text()).slice(-2);
		var houre = ("0" + $("#houre").text()).slice(-2);
		var minits = ("0" + $("#minits").text()).slice(-2);
		var out_date = year + "-" + mounth + "-" + day;
		var out_time = houre + ":" + minits + ":00";
		if (mounth || day || (houre && minits)) {
			return out_date + " " + out_time;
		} else {
			return "";
		}
	}

	function datetimeset() {
		var date = new Date('<?php echo $setdate ?>');
		var y = date.getFullYear();
		var mo = date.getMonth() + 1;
		var d = date.getDate();
		var h = date.getHours();
		var m = date.getMinutes();
		$("#year").text(y);
		$("#mounth").text(("0" + mo).slice(-2));
		$("#day").text(("0" + d).slice(-2));
		$("#houre").text(("0" + h).slice(-2));
		$("#minits").text(("0" + m).slice(-2));
	}
</script>

<div style="width:800px;" id="datetime_entry">
	<div style="float:left;width:400px;margin-top:5%;">
		<div style="font-size:250%;" id="year"><?php echo date("Y"); ?></div>
		<div style="border: 1px #ccc solid;width:80%;height:60px;padding:3%;font-size:150%;vertical-align:middle;margin: 0 0 0 20px;" id="">
			<span id="mounth"></span>月
			<span id="day"></span>日
			<span id="houre"></span>時
			<span id="minits"></span>分
		</div>

		<div style="margin:10%;">
			<!-- <button type="button" style="width:150px;font-size:180%;" class="<?php echo $bclass ?>" onclick="btn_entry();" value="登録">決定</button> -->
			<button type="button" style="width:150px;font-size:140%;" class="<?php echo $bclass ?>" onclick="btn_clear();" value="クリア">クリア</button>
			<button type="button" style="width:150px;font-size:140%;" class="<?php echo $bclass ?>" onclick="datetimeset();" value="現日時">初期日時</button>
		</div>
	</div>
	<div style="float:left;width:354px;">
		<div style="float:left; border: 4px #ccc double;width:87.5%;height:1.3em;font-size:300%;padding:2px;background-color:#000;color:#fff;margin: 0 auto;" id="view_date">

		</div>
		<div style="float:left;width:350px;">
			<div style="display:block;float:left;">
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="7">7</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="8">8</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="9">9</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_mounth();">月</button>
			</div>
			<div style="clear:both; height:8px;"></div>
			<div style="display:block;float:left;">
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="4">4</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="5">5</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="6">6</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_day();">日</button>
			</div>
			<div style="clear:both; height:8px;"></div>
			<div style="display:block;float:left;">
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="1">1</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="2">2</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="3">3</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_houre();">時</button>
			</div>
			<div style="clear:both; height:8px;"></div>
			<div style="display:block;float:left;">
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="0">0</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_click(this.value);" value="C">C</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_year(this.value);" value="年">年</button>
				<button type="button" <?php echo $style ?> class="<?php echo $bclass ?>" onclick="btn_minits();">分</button>
			</div>
			<div style="clear:both; height:8px;"></div>
		</div>

	</div>

	<div style="clear:both;"></div>

</div>
