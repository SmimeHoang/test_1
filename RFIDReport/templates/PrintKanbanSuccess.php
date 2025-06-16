<style>

</style>

<script>

  $(function(){
    $("button").button();
  });

  function printData() {
    // let burl = 'KanbanPDF?ac=Print&mode=lot4';//&mode=Complete'
    let d = {"p":[
      {
        "code":"PL500027-00",
        "form":"2",
        "cav":"16",
        "mlot":"231201",
        "mid":"123456",
        "number":"192",
        "m_date":"2023-12-27"
      },
      {
        "code":"PL500027-00",
        "form":"2",
        "cav":"17",
        "mlot":"231201",
        "mid":"123456",
        "number":"190",
        "m_date":"2023-12-27"
      }
    ]};
    var obj = {};
    for(key in d){
      obj[key] = d[key];
    }
    
    // PDFをbase64エンコードしてURLエンコード済みのデータを取得
    var base64_pdf_data = $.ajax({
      type: 'POST',
      url: 'KanbanPDF?ac=Print&mode=lot4',
      dataType: 'text',
      data:obj,
      async: false,
    }).responseText;
    let thisPage = window.location.href;    
    let url = 'siiprintagent://1.0/print?' +
              'CallbackSuccess='  + encodeURIComponent(thisPage) + '&' +
              'CallbackFail='     + encodeURIComponent(thisPage) + '&' +
              'Format='           + 'pdf' + '&' +
              'Data='             + base64_pdf_data + '&' +
              'SelectOnError='    + 'yes' + '&' +
              'CutType='          + 'full' + '&' +
              'CutFeed='          + 'yes' + '&' +
              'FitToWidth='       + 'yes' + '&' +
              'PaperWidth='       + '58' + '&' +
              'Rotation='         + '0';
    location.href = url;
    // $("#msg").text("");
    $("#msg").text(base64_pdf_data);

  }
</script>
<div id="msg"></div>
<button class="button" onclick="printData();">PDF</button>