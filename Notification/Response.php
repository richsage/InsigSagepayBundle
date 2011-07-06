<?php

namespace Insig\SagepayBundle\Notification;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Implemented according to the Sagepay Server Protocol and Integration
 * Guideline (Protocol version 2.23)
 *
 * A4: You acknowledge receipt of the Notification POST
 * This is the plain text response part of the POST originated by the Server
 * in the step above (A3). Encoding must be as Name=Value fields separated by
 * carriage-return-linefeeds (CRLF).
 */

class Response
{
    // Alphabetic. Max 20 characters. OK, INVALID OR ERROR ONLY.
    /**
     * @Assert\NotBlank()
     * @Assert\Choice({"OK", "INVALID", "ERROR"})
     */
    protected $status;

    // Alphanumeric. Max 255 characters. RFC 1738
    /**
     * @Assert\NotBlank()
     * @Assert\MaxLength(255)
     * @Assert\Url(protocols = {"http", "https"})
     */
    protected $redirectUrl;

    // Alphanumeric. Max 255 characters.
    /**
     * @Assert\MaxLength(255)
     */
    protected $statusDetail;

    // public API ------------------------------------------------------------

    public function __construct($status, $redirectUrl, $statusDetail = null)
    {
        $this->status = $status;
        $this->redirectUrl = $redirectUrl;
        $this->statusDetail = $statusDetail;
    }

    // output ----------------------------------------------------------------

    public function toArray()
    {
        /**
         * Returns an associative array of properties
         * Keys are in the correct Sagepay naming format
         * Empty keys are removed
         */
        return array_filter(
            array(
                'Status'        => $this->status,
                'RedirectURL'   => $this->redirectUrl,
                'StatusDetail'  => $this->statusDetail
            )
        );
    }

    public function getQueryString()
    {
        return str_replace('&', "\r\n", http_build_query($this->toArray()));
    }
}