Feature: Reply to a comment of a given post

    Background:

        Given the author "John Doe" identified by id "1-john" posted an post with id "post-1-id"

    Scenario: Janet posts first valid comment
        Given the author "Janet" with id "2-Janet" sends a comment to the post "post-1-id" with payload
        """
        Hey! I'm Janet
        """
        Then a new Comment is created
        And the comment number 0 has "0" child comments

        Given the author "Henry" with id "3-Henry" sends a comment to the post "post-1-id" with payload
        """
        Hey! This is Henry!
        """
        Then a new Comment is created
        And the comment number 1 has "0" child comments

        Given the author "anonymous" with id "anonymous" is not identified
        Then the post "post-1-id" should have 2 comments
        And the author of comment number 0 is "Janet"
        And the comment number 0 should have "Hey! I'm Janet" content

        Given the author "Hendry" with id "4-Hendry" sends a reply to the comment number "0" with payload
        """
        "Hi!Hi!"
        """
        Then the post "post-1-id" should have 2 comments
        And the comment number 0 has "1" child comments
        And the comment number 1 has "0" child comments
