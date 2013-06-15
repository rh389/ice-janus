<?php
namespace Ice\MailerBundle\Event;

/**
 * Class MailerEvents
 * @package Ice\MailerBundle\Event
 *
 * Exposes consts with the names of events fired by MailerBundle
 */
class MailerEvents
{
    const POST_CREATE_REQUEST = 'ice_mailer.post_create_request';

    const PRE_COMPILE_MAIL = 'ice_mailer.pre_compile_mail';
}