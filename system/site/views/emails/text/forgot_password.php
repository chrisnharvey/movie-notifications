Hi <?=$user['username']?>,

So you've forgotten your password? Not to worry, simply click the link below (or copy and paste it into your browsers address bar) and you can reset it.

<?=site_url('login/forgot')?>?identity=<?=$user['username']?>&key=<?=$key?>

If you did not reset your password then you may disregard this email