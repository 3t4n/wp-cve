<?php

namespace UpsFreeVendor\GuzzleHttp\Psr7;

use UpsFreeVendor\Psr\Http\Message\MessageInterface;
use UpsFreeVendor\Psr\Http\Message\RequestInterface;
use UpsFreeVendor\Psr\Http\Message\StreamInterface;
use UpsFreeVendor\Psr\Http\Message\UriInterface;
/**
 * Returns the string representation of an HTTP message.
 *
 * @param MessageInterface $message Message to convert to a string.
 *
 * @return string
 *
 * @deprecated str will be removed in guzzlehttp/psr7:2.0. Use Message::toString instead.
 */
function str(\UpsFreeVendor\Psr\Http\Message\MessageInterface $message)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Message::toString($message);
}
/**
 * Returns a UriInterface for the given value.
 *
 * This function accepts a string or UriInterface and returns a
 * UriInterface for the given value. If the value is already a
 * UriInterface, it is returned as-is.
 *
 * @param string|UriInterface $uri
 *
 * @return UriInterface
 *
 * @throws \InvalidArgumentException
 *
 * @deprecated uri_for will be removed in guzzlehttp/psr7:2.0. Use Utils::uriFor instead.
 */
function uri_for($uri)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Utils::uriFor($uri);
}
/**
 * Create a new stream based on the input type.
 *
 * Options is an associative array that can contain the following keys:
 * - metadata: Array of custom metadata.
 * - size: Size of the stream.
 *
 * This method accepts the following `$resource` types:
 * - `Psr\Http\Message\StreamInterface`: Returns the value as-is.
 * - `string`: Creates a stream object that uses the given string as the contents.
 * - `resource`: Creates a stream object that wraps the given PHP stream resource.
 * - `Iterator`: If the provided value implements `Iterator`, then a read-only
 *   stream object will be created that wraps the given iterable. Each time the
 *   stream is read from, data from the iterator will fill a buffer and will be
 *   continuously called until the buffer is equal to the requested read size.
 *   Subsequent read calls will first read from the buffer and then call `next`
 *   on the underlying iterator until it is exhausted.
 * - `object` with `__toString()`: If the object has the `__toString()` method,
 *   the object will be cast to a string and then a stream will be returned that
 *   uses the string value.
 * - `NULL`: When `null` is passed, an empty stream object is returned.
 * - `callable` When a callable is passed, a read-only stream object will be
 *   created that invokes the given callable. The callable is invoked with the
 *   number of suggested bytes to read. The callable can return any number of
 *   bytes, but MUST return `false` when there is no more data to return. The
 *   stream object that wraps the callable will invoke the callable until the
 *   number of requested bytes are available. Any additional bytes will be
 *   buffered and used in subsequent reads.
 *
 * @param resource|string|int|float|bool|StreamInterface|callable|\Iterator|null $resource Entity body data
 * @param array                                                                  $options  Additional options
 *
 * @return StreamInterface
 *
 * @throws \InvalidArgumentException if the $resource arg is not valid.
 *
 * @deprecated stream_for will be removed in guzzlehttp/psr7:2.0. Use Utils::streamFor instead.
 */
function stream_for($resource = '', array $options = [])
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Utils::streamFor($resource, $options);
}
/**
 * Parse an array of header values containing ";" separated data into an
 * array of associative arrays representing the header key value pair data
 * of the header. When a parameter does not contain a value, but just
 * contains a key, this function will inject a key with a '' string value.
 *
 * @param string|array $header Header to parse into components.
 *
 * @return array Returns the parsed header values.
 *
 * @deprecated parse_header will be removed in guzzlehttp/psr7:2.0. Use Header::parse instead.
 */
function parse_header($header)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Header::parse($header);
}
/**
 * Converts an array of header values that may contain comma separated
 * headers into an array of headers with no comma separated values.
 *
 * @param string|array $header Header to normalize.
 *
 * @return array Returns the normalized header field values.
 *
 * @deprecated normalize_header will be removed in guzzlehttp/psr7:2.0. Use Header::normalize instead.
 */
function normalize_header($header)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Header::normalize($header);
}
/**
 * Clone and modify a request with the given changes.
 *
 * This method is useful for reducing the number of clones needed to mutate a
 * message.
 *
 * The changes can be one of:
 * - method: (string) Changes the HTTP method.
 * - set_headers: (array) Sets the given headers.
 * - remove_headers: (array) Remove the given headers.
 * - body: (mixed) Sets the given body.
 * - uri: (UriInterface) Set the URI.
 * - query: (string) Set the query string value of the URI.
 * - version: (string) Set the protocol version.
 *
 * @param RequestInterface $request Request to clone and modify.
 * @param array            $changes Changes to apply.
 *
 * @return RequestInterface
 *
 * @deprecated modify_request will be removed in guzzlehttp/psr7:2.0. Use Utils::modifyRequest instead.
 */
function modify_request(\UpsFreeVendor\Psr\Http\Message\RequestInterface $request, array $changes)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Utils::modifyRequest($request, $changes);
}
/**
 * Attempts to rewind a message body and throws an exception on failure.
 *
 * The body of the message will only be rewound if a call to `tell()` returns a
 * value other than `0`.
 *
 * @param MessageInterface $message Message to rewind
 *
 * @throws \RuntimeException
 *
 * @deprecated rewind_body will be removed in guzzlehttp/psr7:2.0. Use Message::rewindBody instead.
 */
function rewind_body(\UpsFreeVendor\Psr\Http\Message\MessageInterface $message)
{
    \UpsFreeVendor\GuzzleHttp\Psr7\Message::rewindBody($message);
}
/**
 * Safely opens a PHP stream resource using a filename.
 *
 * When fopen fails, PHP normally raises a warning. This function adds an
 * error handler that checks for errors and throws an exception instead.
 *
 * @param string $filename File to open
 * @param string $mode     Mode used to open the file
 *
 * @return resource
 *
 * @throws \RuntimeException if the file cannot be opened
 *
 * @deprecated try_fopen will be removed in guzzlehttp/psr7:2.0. Use Utils::tryFopen instead.
 */
function try_fopen($filename, $mode)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Utils::tryFopen($filename, $mode);
}
/**
 * Copy the contents of a stream into a string until the given number of
 * bytes have been read.
 *
 * @param StreamInterface $stream Stream to read
 * @param int             $maxLen Maximum number of bytes to read. Pass -1
 *                                to read the entire stream.
 *
 * @return string
 *
 * @throws \RuntimeException on error.
 *
 * @deprecated copy_to_string will be removed in guzzlehttp/psr7:2.0. Use Utils::copyToString instead.
 */
function copy_to_string(\UpsFreeVendor\Psr\Http\Message\StreamInterface $stream, $maxLen = -1)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Utils::copyToString($stream, $maxLen);
}
/**
 * Copy the contents of a stream into another stream until the given number
 * of bytes have been read.
 *
 * @param StreamInterface $source Stream to read from
 * @param StreamInterface $dest   Stream to write to
 * @param int             $maxLen Maximum number of bytes to read. Pass -1
 *                                to read the entire stream.
 *
 * @throws \RuntimeException on error.
 *
 * @deprecated copy_to_stream will be removed in guzzlehttp/psr7:2.0. Use Utils::copyToStream instead.
 */
function copy_to_stream(\UpsFreeVendor\Psr\Http\Message\StreamInterface $source, \UpsFreeVendor\Psr\Http\Message\StreamInterface $dest, $maxLen = -1)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Utils::copyToStream($source, $dest, $maxLen);
}
/**
 * Calculate a hash of a stream.
 *
 * This method reads the entire stream to calculate a rolling hash, based on
 * PHP's `hash_init` functions.
 *
 * @param StreamInterface $stream    Stream to calculate the hash for
 * @param string          $algo      Hash algorithm (e.g. md5, crc32, etc)
 * @param bool            $rawOutput Whether or not to use raw output
 *
 * @return string Returns the hash of the stream
 *
 * @throws \RuntimeException on error.
 *
 * @deprecated hash will be removed in guzzlehttp/psr7:2.0. Use Utils::hash instead.
 */
function hash(\UpsFreeVendor\Psr\Http\Message\StreamInterface $stream, $algo, $rawOutput = \false)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Utils::hash($stream, $algo, $rawOutput);
}
/**
 * Read a line from the stream up to the maximum allowed buffer length.
 *
 * @param StreamInterface $stream    Stream to read from
 * @param int|null        $maxLength Maximum buffer length
 *
 * @return string
 *
 * @deprecated readline will be removed in guzzlehttp/psr7:2.0. Use Utils::readLine instead.
 */
function readline(\UpsFreeVendor\Psr\Http\Message\StreamInterface $stream, $maxLength = null)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Utils::readLine($stream, $maxLength);
}
/**
 * Parses a request message string into a request object.
 *
 * @param string $message Request message string.
 *
 * @return Request
 *
 * @deprecated parse_request will be removed in guzzlehttp/psr7:2.0. Use Message::parseRequest instead.
 */
function parse_request($message)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Message::parseRequest($message);
}
/**
 * Parses a response message string into a response object.
 *
 * @param string $message Response message string.
 *
 * @return Response
 *
 * @deprecated parse_response will be removed in guzzlehttp/psr7:2.0. Use Message::parseResponse instead.
 */
function parse_response($message)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Message::parseResponse($message);
}
/**
 * Parse a query string into an associative array.
 *
 * If multiple values are found for the same key, the value of that key value
 * pair will become an array. This function does not parse nested PHP style
 * arrays into an associative array (e.g., `foo[a]=1&foo[b]=2` will be parsed
 * into `['foo[a]' => '1', 'foo[b]' => '2'])`.
 *
 * @param string   $str         Query string to parse
 * @param int|bool $urlEncoding How the query string is encoded
 *
 * @return array
 *
 * @deprecated parse_query will be removed in guzzlehttp/psr7:2.0. Use Query::parse instead.
 */
function parse_query($str, $urlEncoding = \true)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Query::parse($str, $urlEncoding);
}
/**
 * Build a query string from an array of key value pairs.
 *
 * This function can use the return value of `parse_query()` to build a query
 * string. This function does not modify the provided keys when an array is
 * encountered (like `http_build_query()` would).
 *
 * @param array     $params   Query string parameters.
 * @param int|false $encoding Set to false to not encode, PHP_QUERY_RFC3986
 *                            to encode using RFC3986, or PHP_QUERY_RFC1738
 *                            to encode using RFC1738.
 *
 * @return string
 *
 * @deprecated build_query will be removed in guzzlehttp/psr7:2.0. Use Query::build instead.
 */
function build_query(array $params, $encoding = \PHP_QUERY_RFC3986)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Query::build($params, $encoding);
}
/**
 * Determines the mimetype of a file by looking at its extension.
 *
 * @param string $filename
 *
 * @return string|null
 *
 * @deprecated mimetype_from_filename will be removed in guzzlehttp/psr7:2.0. Use MimeType::fromFilename instead.
 */
function mimetype_from_filename($filename)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\MimeType::fromFilename($filename);
}
/**
 * Maps a file extensions to a mimetype.
 *
 * @param $extension string The file extension.
 *
 * @return string|null
 *
 * @link http://svn.apache.org/repos/asf/httpd/httpd/branches/1.3.x/conf/mime.types
 * @deprecated mimetype_from_extension will be removed in guzzlehttp/psr7:2.0. Use MimeType::fromExtension instead.
 */
function mimetype_from_extension($extension)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\MimeType::fromExtension($extension);
}
/**
 * Parses an HTTP message into an associative array.
 *
 * The array contains the "start-line" key containing the start line of
 * the message, "headers" key containing an associative array of header
 * array values, and a "body" key containing the body of the message.
 *
 * @param string $message HTTP request or response to parse.
 *
 * @return array
 *
 * @internal
 *
 * @deprecated _parse_message will be removed in guzzlehttp/psr7:2.0. Use Message::parseMessage instead.
 */
function _parse_message($message)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Message::parseMessage($message);
}
/**
 * Constructs a URI for an HTTP request message.
 *
 * @param string $path    Path from the start-line
 * @param array  $headers Array of headers (each value an array).
 *
 * @return string
 *
 * @internal
 *
 * @deprecated _parse_request_uri will be removed in guzzlehttp/psr7:2.0. Use Message::parseRequestUri instead.
 */
function _parse_request_uri($path, array $headers)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Message::parseRequestUri($path, $headers);
}
/**
 * Get a short summary of the message body.
 *
 * Will return `null` if the response is not printable.
 *
 * @param MessageInterface $message    The message to get the body summary
 * @param int              $truncateAt The maximum allowed size of the summary
 *
 * @return string|null
 *
 * @deprecated get_message_body_summary will be removed in guzzlehttp/psr7:2.0. Use Message::bodySummary instead.
 */
function get_message_body_summary(\UpsFreeVendor\Psr\Http\Message\MessageInterface $message, $truncateAt = 120)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Message::bodySummary($message, $truncateAt);
}
/**
 * Remove the items given by the keys, case insensitively from the data.
 *
 * @param iterable<string> $keys
 *
 * @return array
 *
 * @internal
 *
 * @deprecated _caseless_remove will be removed in guzzlehttp/psr7:2.0. Use Utils::caselessRemove instead.
 */
function _caseless_remove($keys, array $data)
{
    return \UpsFreeVendor\GuzzleHttp\Psr7\Utils::caselessRemove($keys, $data);
}
