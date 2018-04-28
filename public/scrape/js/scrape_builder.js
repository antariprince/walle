;(function(global, $){
 
	var Scraper = function(){
		return new Scraper.init();
	}

	Scraper.prototype = {
		fullName: function(){
			return this.firstName + ' ' + this.lastName;
		},

		validate: function(){
			if(supportedLangs.indexOf(this.language) === -1){
				throw "Invalid language";
			}
		},

		greeting: function(){
			return greetings[this.language]+' '+this.firstName+'!';
		},

		formalGreeting: function(){
			return formalGreetings[this.language]+', '+this.fullName();
		},

		greet: function(formal){
			var msg;

			if(formal){
				msg = this.formalGreeting();
			}
			else{
				msg= this.greeting();
			}
			if(console){
				console.log(msg)
			}
			return this;
		},
		log: function(){
			if(console){
				console.log(logMessages[this.language] + ': '+this.fullName());
			}
			return this;
		},

		setLang: function(lang) {
			this.language = lang;
			this.validate();
			return this;
		},

		HTMLGreeting: function(selector,formal){
			if(!selector){
				throw 'jQUery not loaded';
			}
			if(!selector){
				throw 'Missing jQuery selector';
			}
			var msg;
			if(formal){
				msg = this.formalGreeting();
			}
			else{
				msg=this.greeting();
			}
			$(selector).html(msg);
			return this;
		}

	};

	Scraper.init = function(){
		var self = this;
	}

	Scraper.init.prototype = Greetr.prototype;

	global.Scraper = global.Scraper$ = Scraper;

}(window, jQuery));