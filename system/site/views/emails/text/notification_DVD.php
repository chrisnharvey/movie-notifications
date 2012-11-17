Hi <?=$name?>,

Just a quick message to let you know that <?=$title?> is released to DVD <?=$when?>.

<?=$title?> is released on <?=date('jS M Y', strtotime($date))?>.

<? if ($link): ?>You can buy the DVD for this movie at <?=$link?><? endif; ?>

Best wishes,
Movie Notificaitons