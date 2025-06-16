
<div style="float:left;padding:10px;">
    <div style="">
        <div style="float:left;margin-right:20px;"><label for="user" style="font-weight:bold;">担当者：</label><input type="text" value="" name="user" id="user" readonly="readonly" style="width:180px;font-weight:bold;"/></div>
        <label><b>RFID:</b></label>
        <input id="rfid_progress" class="main-ip" value="" placeholder="RFIDをスキャン" style="width: 350px;height:28px;" ></input>
        <button type="button" onclick="od('rfid_progress','RFID');" class="btn_ditemset" style="margin-left:10px;width:50px;height:35px;background-color:#00c3ff;">
        <span class="ui-icon-camera ui-btn-icon-left" style="position:relative;"></span></button>
        <input id="check_itemcode" type="hidden" value="" />
    </div>
</div>