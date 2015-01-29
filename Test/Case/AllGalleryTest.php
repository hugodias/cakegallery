<?php
/**
 * All Gallery plugin tests
 */
class AllGalleryTest extends CakeTestCase
{

    /**
     * Suite define the tests for this plugin
     *
     * @return void
     */
    public static function suite()
    {
        $suite = new CakeTestSuite('All Gallery test');

        $path = CakePlugin::path('Gallery') . 'Test' . DS . 'Case' . DS;
        $suite->addTestDirectoryRecursive($path);

        return $suite;
    }

}
