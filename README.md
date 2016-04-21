# Open Access Quiz

A simple multiple-choice quiz based on plain PHP, SCSS and jQuery, including questions about Open Access in German.

Starting with a short introduction, the participant is presented with a number of multiple-choice questions. Both the order of questions and answer options are randomized for each run. After an answer has been picked, the correct one is shown along with optional additional information. When all questions have been answered, a score based on the number of correct answers and the overall time required is calculated. Results are logged to a plain text file.

## Requirements

A web server running PHP 5.3.6 or above.

## Compiling & Installing

At first, you need to have [node.js](https://nodejs.org/) with npm version 3 or above installed.

If you just want to install the quiz, run

- `npm install --production` to install the dependencies,
- `gulp` to compile,

then copy the contents of the newly created `dist/` directory to your server. Set the access rights for `dist/data/`: the process running PHP has to have write access, while everyone else should have neither read nor write access.

For development, run

- `npm install` to install the dependencies including dev,
- `gulp watch` to compile and wait for changes.

See `gulpfile.js` for more tasks.

## Editing Questions

Questions are stored in a JSON file at `app/data/questions.json`, which consists of an array of objects. HTML is allowed for all text content.

- `id`: A unique identifier, e.g. `"question1"`.
- `question`: The question, e.g. `"What is this all about?"`.
- `options`: An array of exactly 4 possible answers, e.g. `["Everything", "Nothing", "Any of it", "All of it"]`.
- `answer`: Zero-based index of the correct option, e.g. `0`.
- `info` (optional): Additional text that will be shown after a question has been answered.

## Logging

The results for each participant are logged to `data/questions/scores.txt` with one line per completion.

	1461246275 ?2!1P3T1.3|?4!1P0T2.4|?3!1P1T1.5|?1!0P0T1.6|?5!0P0T7.8 2915

First value is the Unix timestamp at completion, second is the order of questions and each given answer in the format `?{Question ID}!{Correct answer}P{Picked answer}T{Seconds required}` with questions delimited by `|`, third value is the score.
