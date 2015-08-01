# mashup

This project imports Google News RSS feeds, using underscore.js template utility, into Google Maps, using their API, 
to create a visual depiction of the current news, using markers at different locations. First a file containing
all the towns in America is compiled into a MYSQL database (compiled in the /bin folder), then we use
this file to implement search.php, which I wrote to implement a search that accepts a range of town,
state and zipcode formats. From here, using typeahead.js, we're able to select the best match using autocomplete. 

In my main javascript file, scripts.js, we utilize underscore.js which provides a great option to 
compile and render the news article's tiles and links(read in from articles.js).After the current news articles have 
been uploaded to the map,Jquery is used, to allow the user the ability to move around the map and utilize the 
search feature, to have the markers remove, then update to their new positions.
