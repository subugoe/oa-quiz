<div class="quiz_row">
	<h1>Frage <?=$model->number?> von <?=$model->questionsCount?></h1>
	<span class="quiz_time">
		<i class="fa fa-play"></i>
		<span class="quiz_clock" data-start="<?=round($model->time)?>" title="Verstrichene Zeit">
			<?=gmdate('G:i:s', $model->time)?>
		</span>
	</span>
</div>

<p class="quiz_question">
	<i class="fa fa-quote-right"></i>&nbsp;<?=$model->question->question?>&nbsp;<i class="fa fa-quote-left"></i>
</p>

<form action="?answer" method="post">
	<ul class="quiz_list">
		<?php foreach ($model->question->options as $index => $option) { ?>
			<li class="quiz_item">
				<input class="quiz_radio" id="option<?=$index?>" type="radio" name="pick" value="<?=$index?>">
				<label class="quiz_option" for="option<?=$index?>">
					<i class="fa fa-check"></i>
					<span class="quiz_option-text">
						<?=$option?>
					</span>
				</label>
			</li>
		<?php } ?>
	</ul>
	<p class="quiz_p -center">
		<button class="quiz_button" type="submit">
			Weiter <i class="fa fa-chevron-right"></i>
		</button>
	</p>
</form>
