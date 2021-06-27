<?php

namespace Vonage\Video;

class Role
{
    /**
    * A subscriber can only subscribe to streams.
    * @var string
    */
    const SUBSCRIBER = 'subscriber';

    /**
    * A publisher can publish streams, subscribe to streams, and signal. (This is the default
    * value if you do not set a role.)
    * @var string
    */
    const PUBLISHER = 'publisher';

    /**
    * In addition to the privileges granted to a publisher, in clients using the OpenTok.js
    * library, a moderator can call the forceUnpublish() and forceDisconnect() methods of
    * the Session object.
    * @var string
    */
    const MODERATOR = 'moderator';
}
