Hi <?=$name?>,

Just a quick message to let you know that <?=$title?> is released in theaters <?=$when?>.

<?=$title?> is released on <?=date('jS M Y', strtotime($date))?>.

<? if ($link): ?>You can buy tickets to see this movie at <?=$link?><? endif; ?>

Best wishes,
Movie Notificaitons