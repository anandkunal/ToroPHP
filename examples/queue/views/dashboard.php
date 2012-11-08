<h1>Toro Queue</h1>

<strong>Queue Size:</strong> <span id="size"><?= $stats['size']; ?></span> | 
<strong>Total Sends:</strong> <span id="sends"><?= $stats['sends']; ?></span> |
<strong>Total Receives:</strong> <span id="receives"><?= $stats['receives']; ?></span><br/>

<hr />

<h2>Send</h2>
<form action="./send" method="post">
<textarea name="payload"></textarea><br/>
<input type="submit" value="Send" />
</form>

<hr />

<h2>Receive</h2>
<div id="payload"></div><br/>
<input type="submit" value="Receive" onclick="receive(); return false;" />

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
function update() {
  $.get("./stats", function(b) {
    $('#size').html(b.size);
    $('#sends').html(b.sends);
    $('#receives').html(b.receives);
  });
}

function receive() {
  $.get("./receive", function(a) {
    $('#payload').html(a.payload);
    update();
  });
}
</script>