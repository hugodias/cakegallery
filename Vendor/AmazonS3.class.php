<?php
require App::pluginPath('Gallery') . 'Vendor' . DS . 'aws' . DS . 'aws-autoloader.php';

use Aws\S3\S3Client;


class AmazonS3
{
    public $s3Object = null;

    public $objects = array();

    private $bucket = null;

    function __construct($credentials = array(), $bucket = null)
    {
        try {
            $this->_configureCredentials($credentials['API_KEY'], $credentials['SECRET_KEY']);

            $this->setBucket($bucket);

        } catch (\Aws\CloudFront\Exception\Exception $e) {
            print($e->getMessage());
            exit;
        }
    }

    /**
     * Configure AWS credentials for Amazon s3 use.
     *
     * @param $key
     * @param $secret
     */
    private function _configureCredentials($key, $secret)
    {
        $this->s3Object = S3Client::factory(array(
            'key' => $key,
            'secret' => $secret,
        ));
    }

    /**
     * Generate a name for bucket based on the current CakePHP chiperSeed
     *
     * This name should be unique, so there is a chance that any other amazon already
     * have this bucket name used. What you need to do is specify an extra value in this
     * bucket name.
     *
     * PS: Do NOT use a random value function, because if you do, this class will
     * create a new bucket in your account every time you upload a new file.
     *
     * @return string
     */
    private function generateBucketName()
    {
        return 'cakegallery-' . Configure::read('Security.cipherSeed');
    }

    /**
     * Create a new bucket in Amazon S3.
     *
     * The bucketName should be UNIQUE.
     *
     * @param null $bucketName
     * @return array
     */
    public function createBucket($bucketName = null)
    {
        $bucket = $this->s3Object->createBucket(array(
            'Bucket' => $bucketName,
            'LocationConstraint' => 'us-west-2',
        ));

        $this->s3Object->waitUntil('BucketExists', array('Bucket' => $bucketName));

        return $bucketName;
    }

    /**
     * Check if a particular bucket is in the current Amazon s3 account
     *
     * @param $bucketName
     * @return bool
     */
    public function hasBucket($bucketName)
    {
        $buckets = $this->s3Object->listBuckets();

        foreach ($buckets['Buckets'] as $bucket) {

            if ($bucket['Name'] == $bucketName)
                return true;
        }

        return false;
    }

    /**
     * @return null
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param null $bucket
     */
    public function setBucket($bucket = null)
    {
        # Dont specified the bucket
        if (!$bucket) {

            /**
             * Search for a bucket based on name generated with generateBucketName() function
             * If he has no bucket with this name so we'll create a new one with this name
             *
             * @see generateBucketName()
             */
            if (!$this->hasBucket($this->generateBucketName())) {

                $this->_saveBucketCredentials(
                    $this->createBucket(
                        $this->generateBucketName()
                    )
                );

            } else {
                $this->_saveBucketCredentials($this->generateBucketName());
            }

        } else {
            $this->_saveBucketCredentials($bucket);
        }
    }

    /**
     * Store the bucket credentials in this classe for future uses
     *
     * @param $bucket
     * @param null $request_id
     * @param null $location
     */
    private function _saveBucketCredentials($bucket)
    {
        $this->bucket = $bucket;
    }

    /**
     * Get all objects from S3 bucket
     *
     * @return array
     */
    public function retrieve()
    {
        $iterator = $this->s3Object->getIterator('ListObjects', array(
            'Bucket' => $this->bucket
        ));

        foreach ($iterator as $object) {
            $objects[] = $object;
        }

        return $objects;
    }

    /**
     * Upload a file to S3 predefined bucket
     *
     * Return all information about the file
     *
     * @param $file_path
     * @param $filename
     * @return mixed
     */
    public function upload($file_path, $filename)
    {
        if (!$this->bucket) {
            throw new InvalidArgumentException('You need to specify the bucket');
        }

        $result = $this->s3Object->putObject(array(
            'Bucket' => $this->bucket,
            'Key' => $filename,
            'SourceFile' => $file_path
        ));

        // We can poll the object until it is accessible
        $this->s3Object->waitUntil('ObjectExists', array(
            'Bucket' => $this->bucket,
            'Key' => $filename
        ));

        return $result;
    }
}