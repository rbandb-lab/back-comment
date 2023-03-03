Feature: Create a first comment to a given post

    Background:
        Given the author "John Doe" identified by id "1-john" posted an post with id "post-1-id"

    Scenario: Janet posts first comment
        Given the author "Janet" sends a comment to the post "post-1-id" with payload:
        """
            Lorem Elsass ipsum Pellentesque jetz gehts los sit Pfourtz ! hopla DNA, aliquam knack Strasbourg baeckeoffe und varius salu id, sit mänele Heineken hopla in, geht's Huguette geïz id auctor, elit Spätzle nüdle hop quam, rhoncus senectus Yo dû. Morbi leverwurscht amet, Racing. Coopé de Truchtersheim Salu bissame semper leo hoplageiss gal turpis elementum rucksack suspendisse tristique munster blottkopf, Kabinetpapier schnaps Oberschaeffolsheim sagittis purus ante ftomi! merci vielmols Chulien libero, Verdammi vielmols, libero, pellentesque risus, s'guelt gravida gewurztraminer kougelhopf Carola condimentum tchao bissame eleifend kuglopf habitant dolor libero.
        """
        Then an "Comment\Exception\InvalidCommentContentException" exception is thrown
        And the message text should contain the following keywords:
        """
        |content|too long| 255 characters|
        """


    Scenario: Janet posts first comment but it is too short
        Given the author "Janet" sends a comment to the post "post-1-id" with payload:
        """
        """
        Then an "Comment\Exception\InvalidCommentContentException" exception is thrown
        And the message text should contain the following keywords:
        """
        |content|too short| 2 characters|
        """

    Scenario: Janet posts first valid comment
        Given the author "Janet" sends a comment to the post "post-1-id" with payload:
        """
        "Hey!"
        """
        Then a new Comment is created
        And the Comment is added to the post "post-1-id"
