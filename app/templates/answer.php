<div class="quiz_row">
	<h1>Frage <?=$model->number?> von <?=$model->questionsCount?></h1>
	<span class="quiz_time" data-start="<?=round($model->time)?>" title="Zeit angehalten">
		<i class="fa fa-pause"></i>
		<?=gmdate('G:i:s', $model->time)?>
	</span>
</div>

<p class="quiz_question">
	<i class="fa fa-quote-right"></i>&nbsp;<?=$model->question?>&nbsp;<i class="fa fa-quote-left"></i>
</p>

<p class="quiz_p -small">Ihre Antwort:</p>
<?php if ($model->answeredCorrectly) { ?>
	<p class="quiz_answer -correct">
		<i class="fa fa-check"></i>
		<span class="quiz_answer-text"><?=$model->correctAnswer?></span>
	</p>
	<p class="quiz_p"><?=( rand(0, 2) > 1 ? 'Das ist richtig.' : 'So ist es.' )?></p>
<?php } else { ?>
	<p class="quiz_answer -false">
		<i class="fa fa-times"></i>
		<span class="quiz_answer-text"><?=$model->pickedAnswer?></span>
	</p>
	<p class="quiz_p">Das ist leider falsch. Richtig wäre: <b><?=$model->correctAnswer?></b>
<?php } ?>

<?php if ($model->info) { ?>
	<p class="quiz_p"><?=$model->info?></p>
<?php } ?>

<p class="quiz_p -center">
	<?php if ($model->moreQuestions) { ?>
		<a class="quiz_button" href="?question">
			Nächste Frage <i class="fa fa-chevron-right"></i>
		</a>
	<?php } else { ?>
		<a class="quiz_button" href="?results">
			Ergebnis <i class="fa fa-chevron-right"></i>
		</a>
	<?php } ?>
</p>
