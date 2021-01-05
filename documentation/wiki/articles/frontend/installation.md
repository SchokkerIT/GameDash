## Installation

The config/config.json is loaded before any other assets are and is required for the initialisation.

The only setting worth mentioning are `api.address` and `cookie.domain`.

`api.address` sets the API address, so the frontend knows where to find your API server.

`cookie.domain` sets the scope for where cookies that are set by the frontend are valid. If for instance you've created a custom integration with the billing API running on another sub domain and you want user's session to carry over, you make sure that the scope covers both of those domains.
