<?php
namespace subugoe\oaquiz;

class Quiz {
	private $questions = [];
	private $questionsOrder = [];
	private $correctCount = 0;
	private $maxQuestions = 10;
	private $shuffleQuestions = true;
	private $shuffleOptions = true;
	private $questionsFile = 'data/questions.json';
	private $logFile = 'logs/scores.txt';
	private $logFileGranular = 'logs/answers.txt';

	function __construct() {
		// First GET key is the desired route
		$route = key($_GET);

		if ( ! touch($this->logFile) or ! touch($this->logFileGranular) ) {
			die('Cannot write log files. Make sure the logs directory is writable.');
		}

		if ( $route === 'start' ) {
			$this->startNew();
		} elseif ( $route === 'question' ) {
			$this->showQuestion();
		} elseif ( $route === 'answer' ) {
			// Force positive integer
			$pick = isset($_POST['pick']) ? intval(preg_replace('/\D/', '', $_POST['pick'])) : false;
			if ( $pick === false ) {
				$this->showQuestion();
			} else {
				$this->checkAnswer($pick);
			}
		} elseif ( $route === 'results' ) {
			$this->showResults();
		} else {
			$this->showStartPage();
		}
	}

	function showStartPage() {
		// Set to check if cookies are enabled
		setcookie('test', 'test', time() + 3600);

		$model = new \stdClass();
		$model->minutes = $this->maxQuestions;
		$model->showShareButtons = true;
		$this->renderTemplate('start', $model);
	}

	function startNew() {
		$this->initSession();

		$_SESSION = [];
		$_SESSION['correctCount'] = 0;
		$_SESSION['lastQuestionShown'] = -1;
		$_SESSION['lastQuestionAnswered'] = -1;
		$_SESSION['timeElapsed'] = 0;
		$_SESSION['results'] = [];
		$_SESSION['sessionStarted'] = microtime(true);
		$_SESSION['score'] = false;

		header('location: ' . $this->urlWithoutParams() . '?question', true, 303); // "See Other"
	}

	function showQuestion() {
		$this->initSession();

		$number = $_SESSION['lastQuestionAnswered'] + 1;
		if ( $number >= count($this->questions) ) {
			$this->showResults();
			return false;
		}
		if ( $_SESSION['lastQuestionShown'] < $number ) {
			$_SESSION['timestamp'] = microtime(true);
		}
		$_SESSION['lastQuestionShown'] = $number;

		$model = new \stdClass();
		$model->question = $this->questions[$number];
		$model->questionsCount = count($this->questions);
		$model->number = $number + 1;
		$model->time = round($_SESSION['timeElapsed'] + microtime(true) - $_SESSION['timestamp']);
		$this->renderTemplate('question', $model);
	}

	function checkAnswer($pickedOption) {
		$this->initSession();

		$number = $_SESSION['lastQuestionShown'];
		$question = $this->questions[$number];
		if ( $_SESSION['lastQuestionAnswered'] < $number ) {
			$timeRequired = microtime(true) - $_SESSION['timestamp'];
			$_SESSION['timeElapsed'] += $timeRequired;
			$answerIsCorrect = 0;
			if ( $pickedOption === $question->answer ) {
				$_SESSION['correctCount']++;
				$answerIsCorrect = 1;
			}
			$timeRounded = round($timeRequired, 1);
			$_SESSION['results'][] = "?{$this->questions[$number]->id}!{$question->answer}P{$pickedOption}={$answerIsCorrect}T{$timeRounded}";
			file_put_contents($this->logFileGranular, session_id() . ' ' . $_SESSION['results'][$number] . PHP_EOL, FILE_APPEND);
		}
		$_SESSION['lastQuestionAnswered'] = $number;

		$model = new \stdClass();
		$model->question = $question->question;
		$model->answeredCorrectly = ( $pickedOption === $question->answer );
		$model->correctAnswer = $question->options[$question->answer];
		$model->pickedAnswer = $question->options[$pickedOption];
		$model->info = ( isset($question->info) ? $question->info : '' );
		$model->questionsCount = count($this->questions);
		$model->number = $number + 1;
		$model->moreQuestions = ( $number + 1 < count($this->questions) );
		$model->time = round($_SESSION['timeElapsed']);
		$this->renderTemplate('answer', $model);
	}

	function showResults() {
		$this->initSession();

		$quizRunning = isset($_SESSION['lastQuestionAnswered']) && $_SESSION['lastQuestionAnswered'] >= 0;
		$quizCompleted = $_SESSION['lastQuestionAnswered'] === count($this->questions) - 1;
		if ( ! $quizRunning || ! $quizCompleted ) {
			header('location: ' . $this->urlWithoutParams() . '?question', true, 303); // "See Other"
		}

		$model = new \stdClass();
		$model->correctCount = $_SESSION['correctCount'];
		$model->allCount = count($this->questions);
		$model->ratio = $model->correctCount / $model->allCount;
		$model->time = round($_SESSION['timeElapsed']);

		// Get others' scores and calculate position
		$otherScores = file($this->logFile, FILE_IGNORE_NEW_LINES);
		if ( $_SESSION['score'] === false ) {
			$_SESSION['score'] = round( $model->allCount * 1000 * $model->ratio / (1 + $_SESSION['timeElapsed'] / $model->allCount * .01) );
			file_put_contents($this->logFile, time() . ' ' . implode('|', $_SESSION['results']) . ' ' . $_SESSION['score'] . PHP_EOL, FILE_APPEND);
		}
		$model->score = $_SESSION['score'];
		$allScores = [];
		$lowerScores = [];
		foreach ( $otherScores as $line ) {
			$parts = explode(' ', $line);
			$otherScore = $parts[2];
			if ( $model->score > $otherScore) {
				$lowerScores[] = $otherScore;
			}
			$allScores[] = $otherScore;
		}
		$model->averageScore = $otherScores ? round( array_sum($allScores) / count($otherScores) ) : 0;
		$model->scorePercentage = $otherScores ? round( count($lowerScores) / count($otherScores) * 100 ) : 0;

		// Cheating detection :P (over 80% correct, but less than one second per answer)
		$model->cheater = ( $model->ratio > .8 && $model->time < count($this->questions) );

		$model->showShareButtons = true;
		$this->renderTemplate('results', $model);
	}

	private function initSession() {
		session_start();

		if ( ! isset($_SESSION['sessionStarted']) || ! isset($_COOKIE['test']) || ! $_COOKIE['test'] === 'test') {
			header('location: ' . $this->urlWithoutParams(), true, 303); // "See Other"
		}

		if ( isset($_SESSION['questions']) ) {
			$this->questions = $_SESSION['questions'];
			return true;
		}

		$questionsJson = file_get_contents($this->questionsFile);
		$this->questions = (array)json_decode($questionsJson);

		if ( ! $this->questions ) {
			trigger_error('Questions could not be loaded', E_USER_ERROR);
			die();
		}

		if ( $this->shuffleQuestions ) {
			shuffle($this->questions);
		}
		if ( $this->shuffleOptions ) {
			foreach ( $this->questions as &$question ) {
				$this->shuffleAssoc($question->options);
			}
		}

		$this->questions = array_slice($this->questions, -$this->maxQuestions);
		$_SESSION['questions'] = $this->questions;
	}

	private function renderTemplate($template, $model = null) {
		require 'templates/_html.php';
	}

	private function shuffleAssoc(&$array) {
		$keys = array_keys($array);
		shuffle($keys);
		foreach($keys as $key) {
			$new[$key] = $array[$key];
		}
		$array = $new;
		return true;
	}

	private function urlWithoutParams() {
		if ($_SERVER['SERVER_PORT'] != '80') {
			$url = '//' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
		} else {
			$url = '//' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		}
		return strtok($url, '?');
	}
}
