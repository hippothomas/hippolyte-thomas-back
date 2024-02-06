<?php

namespace App;

class Constants
{
    /**
     * The current release version
     * Follows the Semantic Versioning strategy: https://semver.org/.
     */
    public const string VERSION = '2.0.0';
    /**
     * The current release: major * 10000 + minor * 100 + patch.
     */
    public const int VERSION_ID = 20000;
    /**
     * Main Website URL.
     */
    public const string MAIN_WEBSITE = 'https://hippolyte-thomas.fr/';
}
