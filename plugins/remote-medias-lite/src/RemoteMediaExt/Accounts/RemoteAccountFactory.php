<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts;

use WPRemoteMediaExt\RemoteMediaExt\AccountPostType;

class RemoteAccountFactory
{
    public static function create($rmID)
    {
        return new RemoteAccount($rmID);
    }

    public static function createFromService($serviceClass)
    {
        $service = RemoteServiceFactory::create($serviceClass);
        if (!is_null($service)) {
            $newaccount = new RemoteAccount();
            $newaccount->setService($service);
            $newaccount->setType(RemoteServiceFactory::getType($serviceClass));

            return $newaccount;
        }

        return null;
    }

    public static function getAll(array $optionset = array())
    {
        $returnAccounts = array();

        $rmlaccounts = get_posts(
            array(
                'post_type'      => AccountPostType::POSTTYPE,
                'posts_per_page' => -1,
                'post_status'    => 'publish'
            )
        );

        foreach ($rmlaccounts as $account) {

            $rmlAccount = self::create($account->ID); 

            if (empty($optionset)) {
                $returnAccounts[] = $rmlAccount;
                continue;
            }

            $conditionsMet = true;
            foreach ($optionset as $option => $value) {
                if (!$rmlAccount->hasOption($option, $value)) {
                    $conditionsMet = false;
                }
            }

            if ($conditionsMet === true) {
                $returnAccounts[] = $rmlAccount;
            }
        }

        return $returnAccounts;
    }
}
