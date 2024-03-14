# WPDesk/ShopMagic/Workflow

It's heart and center of ShopMagic. Workflow is a high level construct which covers `Automation`
and its components (`Event`, `Action`, `Filter`, `Placeholder`).

Conceptually, workflow is something that can be initialized upon some event, be validated for
some conditions and then perform some actions. Finally, Workflow result is saved as Outcome.

One level lower in the hierarchy, we have `Automation` which holds a concrete setup for workflow
-- one `Event`, set of `Filter`s and `Action`s. Those elements form a Workflow components, what
gives them some special abilities (i.e. being presented to users and having some settings).

Workflow can delegate action to asynchronous execution.
