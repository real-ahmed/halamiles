<?php



namespace App\Traits;



trait FileInfo

{



    /*

    |--------------------------------------------------------------------------

    | File Information

    |--------------------------------------------------------------------------

    |

    | This trait basically contain the path of files and size of images.

    | All information are stored as an array. Developer will be able to access

    | this info as method and property using FileManager class.

    |

    */



    public function fileInfo(){

        $data['depositVerify'] = [

            'path'      =>'assets/images/verify/deposit'

        ];

        $data['verify'] = [

            'path'      =>'assets/verify'

        ];

        $data['default'] = [

            'path'      => 'assets/images/default.png',

        ];

        $data['ticket'] = [

            'path'      => 'assets/support',

        ];

        $data['language'] = [

            'path'      => 'assets/images/lang',

            'size'      => '64x64',

        ];

        $data['logoIcon'] = [

            'path'      => 'assets/images/logoIcon',

        ];

        $data['favicon'] = [

            'size'      => '128x128',

        ];

        $data['extensions'] = [

            'path'      => 'assets/images/extensions',

            'size'      => '36x36',

        ];

        $data['seo'] = [

            'path'      => 'assets/images/seo',

            'size'      => '1180x600',

        ];

        $data['userProfile'] = [

            'path'      =>'assets/images/user/profile',

            'size'      =>'350x300',

        ];

        $data['adminProfile'] = [

            'path'      =>'assets/admin/images/profile',

            'size'      =>'400x400',

        ];

        $data['coupon'] = [

            'path'      =>'assets/images/coupon',

            'size'      =>'300x200'

        ];

        $data['product'] = [

            'path'      =>'assets/images/product',

            'size'      =>'300x200'

        ];

        $data['store'] = [

            'path'      =>'assets/images/store'

        ];

        $data['countries'] = [

            'path'      =>'assets/images/countries'

        ];

        $data['category'] = [

            'path'      =>'assets/images/category'

        ];

        $data['advertisement'] = [

            'path' => 'assets/images/advertisement',

        ];


        $data['banner'] = [

            'path' => 'assets/images/frontend/banner/',
            'size'      =>'1920x1080'

        ];

        return $data;

	}



}