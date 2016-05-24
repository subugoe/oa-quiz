FROM	ubuntu:15.10


RUN     curl -sL https://deb.nodesource.com/setup_4.x |  bash -

RUN	apt-get update
RUN	apt-get install -y php5 wget unzip apache2 curl nodejs

RUN	npm install --global gulp

RUN	wget https://github.com/subugoe/oa-quiz/archive/master.zip

RUN	unzip master.zip
RUN	rm master.zip
RUN	cd oa-quiz-master && npm install && gulp
RUN	cp -r oa-quiz-master/dist/* /var/www/html
RUN	rm /var/www/html/index.html

EXPOSE	80

CMD	apache2ctl start && tail -f /var/log/apache2/access.log

