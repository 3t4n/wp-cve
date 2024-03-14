To create an Poll-Template, please take the existing ones in reference.

I strongly recommend using the existing (basic) template as a guide

Contents every Template must have:
==  Template-CSS  ==
- .css-File named exactly as the Template Name (Folder Name)

== Template-Files ==
- disabled.html > If the Poll is disabled, this Template will be shown.
- hidden.html > If the Poll is hidden, this Template will be shown.
- result.html > If the Poll-Result is shown, this Template will be used.
- success.html > If the Poll was voted and "Show Results after Vote" is disabled, this "Thanks"-Template will be shown.
- vote.html > This is the actual Poll-Template for showing (the Question) and selecting the answer(s).

== Available/necessary(*) placeholder ==

!! The Outer-Div in vote.html must have an id of "mpp_{{hash}}" !!

= {{idField}} = *necessary
Poll-ID Field to identify the Poll.
Required in: vote.html

= {{nonceField}} = *necessary
Security-Field, for sending Votes.
Required in: vote.html

= {{hash}} = (necessary)
Identifier.
(Necessary in vote.html outer-div.)
Should be on everything that is supposed to this Poll Template (eg. JavaScript Vars)

= {{question}} =
The actual Question as Text.
Should be in: vote.html, result.html

= {{voteButton}} = *necessary
Shows the Vote-Button
Required in: vote.html

= {{resultButton}} =
Shows the Result-Button that leads to the Result
Should be in: vote.html
(Is disabled in vote.html, when it is not allowed to view the Result before vote)

= {{backButton}} =
Shows a Return-To-Vote Button
Should be in: result.html
(Is disabled in result.html, when it is not allowed to vote again.)

/*****************************************************************************************************************
*  Answer placeholders must be inside of the {{answerTemplate_single}} or {{answerTemplate_multi}} placeholders  *
*  These are Looping over every possible Answer                                                                  *
******************************************************************************************************************
*                                                                                                                *
*  = {{answerTemplate_single}} & {{/answerTemplate_single}} =                                                    *
*  Start and End of the inner-Template of an Single-Answer Poll. eg. Radioboxes                                  *
*                                                                                                                *
*  = {{answerTemplate_multi}} & {{/answerTemplate_multi}} =                                                      *
*  Start and End of the inner-Template of an Multi-Answer Poll. eg. Checkboxes                                   *
*                                                                                                                *
*  = {{answerID}} =                                                                                              *
*  The ID of the current Answer.                                                                                 *
*  Should be in: vote.html, result.html                                                                          *
*                                                                                                                *
*  = {{answerTag}} = *necessary                                                                                  *
*  The name-Tag in the input for the current Answer.                                                             *
*  Required in: vote.html                                                                                        *
*                                                                                                                *
*  = {{answerTagID}} =                                                                                           *
*  The TagID for the current Answer.                                                                             *
*  Should be in: vote.html                                                                                       *
*                                                                                                                *
*  = {{answerText}} =                                                                                            *
*  The, obviously, Answer-Text of the current Answer.                                                            *
*  Should be in: vote.html, result.html, success.html                                                            *
*                                                                                                                *
******************************************************************************************************************/









