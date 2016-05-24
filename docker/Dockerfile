FROM	ubuntu:15.10

RUN	apt-get update
RUN	apt-get install -y php5 wget unzip apache2 curl

RUN     curl -sL https://deb.nodesource.com/setup_4.x |  bash -
RUN	apt-get install -y nodejs

RUN	npm install --global gulp

RUN	wget https://github.com/subugoe/oa-quiz/archive/master.zip

RUN	unzip master.zip
RUN	rm master.zip
RUN	cd oa-quiz-master && npm install && gulp
RUN	cp -r oa-quiz-master/dist/* /var/www/html
RUN	rm /var/www/html/index.html
RUN	mkdir /var/www/html/logs/ || /bin/true 		# remove after the log dir is moved to master
RUN	chown -R www-data:www-data /var/www/html/logs/

EXPOSE	80
VOLUME	/var/www/html/logs

CMD	apache2ctl start && tail -f /var/log/apache2/access.log

