Feature: Get all comments of an article

    Background:
        Given the author "John Doe" identified by id "1-john" posted an article with id "article-1-id"
        And  the article has the following text content:
        """
        Did you hear that? They've shut down the main reactor. We'll be destroyed for sure. This is madness! We're doomed! There'll be no escape for the Princess this time. What's that? Artoo! Artoo-Detoo, where are you? At last! Where have you been? They're heading in this direction.
        Why aren't you at your post? TX-four-one-two, do you copy? Take over. We've got a bad transmitter. I'll see what I can do. You know, between his howling and your blasting everything in sight, it's a wonder the whole station doesn't know we're here. Bring them on! I prefer a straight fight to all this sneaking around. We found the computer outlet, sir. Plug in.
        My scope shows the tower, but I can't see the exhaust port! Are you sure the computer can hit it? Watch yourself! Increase speed full throttle! What about the tower? You worry about those fighters! I'll worry about the tower! Artoo...that, that stabilizer's broken loose again! See if you can't lock it down! I'm hit! I can't stay with you. Get clear, Wedge. You can't do any more good back there! Sorry! Let him go! Stay on the leader! Hurry, Luke, they're coming in much faster this time. I can't hold them! Artoo, try and increase the power! Hurry up, Luke! Wait! I'm on the leader.
        He betrayed and murdered your father. Now the Jedi are all but extinct. Vader was seduced by the dark side of the Force. The Force? Well, the Force is what gives a Jedi his power.
        It must be a decoy, sir. Several of the escape pods have been jettisoned. Did you find any droids? No, sir. If there were any on board, they must also have jettisoned.
        """
        Given the author "Janet" with id "2-Janet" sends a comment to the article "article-1-id" with payload
        """
        Hey! I'm Janet
        """
        Given the author "Henry" with id "3-Henry" sends a comment to the article "article-1-id" with payload
        """
        Hey! This is Henry!
        """

    Scenario:
        Given the author "anonymous" with id "anonymous" is not identified
        Then the article "article-1-id" should have 2 comments
        And the author of comment number 0 is "Janet"
        And the comment number 0 should have "Hey! I'm Janet" content
        And the author of comment number 1 is "Henry"
        And the comment number 1 should have "Hey! This is Henry!" content
