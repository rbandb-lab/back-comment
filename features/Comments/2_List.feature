Feature: Get all comments of an post

    Background:
        Given the author "John Doe" identified by id "1-john" posted an post with id "post-1-id"
        Given the author "Janet" with id "2-Janet" sends a comment to the post "post-1-id" with payload
        """
        Hey! I'm Janet
        """
        Given the author "Henry" with id "3-Henry" sends a comment to the post "post-1-id" with payload
        """
        Hey! This is Henry!
        """

    Scenario:
        Given the author "anonymous" with id "anonymous" is not identified
        Then the post "post-1-id" should have 2 comments
        And the author of comment number 0 is "Janet"
        And the comment number 0 should have "Hey! I'm Janet" content
        And the author of comment number 1 is "Henry"
        And the comment number 1 should have "Hey! This is Henry!" content
