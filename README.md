Phony
=====

A test server to be used in automated tests to mock web services.

# FAQ

### What?

The point of this is to allow you to test code that hits an API by mocking the web server. So instead of mocking the client object your change the end point it hits.

### Why?
Most of the time it seems when you need to do something with http requests in tests you end up mocking the http client. You never test that a http request actually gets made. I've found that having a test server to hit reduced the number of simple errors I made like making a GET request instead of a POST request. Also it gives me more confidence that my code actually works as intented.

### You're over coupling your tests

Well you would provide the mock http client the return data, this is pretty much the same except their is an actual HTTP server in this mix.

# TODO

* Add assertRequestWasMade
* Add queue of responses. So if /hello was called once it would serve up one response if it was called again it would give another response.