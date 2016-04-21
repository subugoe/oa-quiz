<?php
namespace subugoe\oaquiz;
?>

<div class="quiz_row">
	<h1>Ergebnis</h1>
	<span class="quiz_time" title="Benötigte Zeit">
		<i class="fa fa-clock-o"></i>
		<?=gmdate('G:i:s', $model->time)?>
	</span>
</div>

<p class="quiz_p">
	Sie haben <?=$model->correctCount?> von <?=$model->allCount?> Fragen richtig beantwortet.
	<?php if ( $model->ratio === 1 ) { ?>
		Super. Falls Sie das nicht ohnehin bereits tun: Helfen Sie uns, Open Access bekannter zu machen!
	<?php } elseif ( $model->ratio >= .8 ) { ?>
		Sehr gut! Sie sind auf dem besten Wege, Open-Access-Expertin/-Experte zu werden.
	<?php } elseif ( $model->ratio >= .5 ) { ?>
		Schon nicht schlecht, aber da ist noch Luft nach oben.
	<?php } elseif ( $model->ratio > .2) { ?>
		Das ist ein Anfang. Am besten gleich nochmal versuchen.
	<?php } else { ?>
		Glüchwunsch, Ihr Ergebnis ist schlechter als das durchschnittliche derjenigen, die einfach zufällig irgendetwas anklicken
		<i class="fa fa-smile-o"></i>
	<?php } ?>
</p>

<p class="quiz_p -small">Ihre Punktzahl:</p>

<p class="quiz_score">
	<?=$model->cheater ? 'Haha, netter Versuch, Cheater!' : $model->score?>
</p>

<?php if ( $model->averageScore ) { ?>
	<p class="quiz_p">
		Damit sind Sie besser als <?=$model->scorePercentage?>&thinsp;% der Teilnehmer.
		Durchschnittlich wurden <?=$model->averageScore?> Punkte erreicht.
	</p>
<?php } ?>

<p class="quiz_p -center">
	<a class="quiz_button" href="<?=$this->urlWithoutParams()?>">
		Noch einmal spielen
	</a>
</p>

<p class="quiz_p -small">
	Hat sich irgendwo ein Fehler eingeschlichen oder haben Sie Verbesserungsvorschläge?
	<a href="http://open-access.net/kontakt/" target="_blank">Wir freuen uns über Ihre Rückmeldung</a>.
</p>

<div class="shariff" data-title="<?=$model->score?> Punkte beim #OpenAccessQuiz! Auch versuchen?">
</div>
