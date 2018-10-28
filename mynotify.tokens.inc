<?php


use Drupal\Core\Datetime\Entity\DateFormat;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\user\Entity\User;

/**
 * Implements hook_token_info().
 */
function mynotify_token_info() {
  $types['mynotify'] = [
    'name' => t('Mynotify'),
    'description' => t('Tokens related to individual mynotify.'),
    'needs-data' => 'mynotify',
  ];
  $mynotify['id'] = [
    'name' => t('Mynotify ID'),
    'description' => t("The unique ID of the mynotify."),
  ];
  $mynotify['name'] = [
    'name' => t("Deprecated: Mynotify Name"),
    'description' => t("Deprecated: Mynotify Name."),
  ];
  $mynotify['mail'] = [
    'name' => t("Email"),
    'description' => t("The email address of the Mynotify."),
  ];
  $mynotify['phone'] = [
    'name' => t("Phone"),
    'description' => t("The phone of the Mynotify."),
  ];
  $mynotify['text'] = [
    'name' => t("Text"),
    'description' => t("The text of the Mynotify."),
  ];
  $mynotify['edit-url'] = [
    'name' => t("Edit URL"),
    'description' => t("The URL of the Mynotify edit page."),
  ];
  $mynotify['created'] = [
    'name' => t("Created"),
    'description' => t("The date the Mynotify was created."),
    'type' => 'date',
  ];
  $mynotify['owner'] = [
    'name' => t("Owner"),
    'type' => 'user',
  ];
  $mynotify['product'] = [
    'name' => t("Product"),
    'type' => 'commerce_product',
  ];
  $mynotify['mails-not-notified'] = [
    'name' => t("Mails list not notified by product"),
    'description' => t("The list mails of the Mynotify product_id."),

  ];
  return [
    'types' => $types,
    'tokens' => ['mynotify' => $mynotify],
  ];
}


/**
 * Implements hook_tokens()
 *
 * @param $type
 * @param $tokens
 * @param array $data
 * @param array $options
 * @param \Drupal\Core\Render\BubbleableMetadata $bubbleable_metadata
 * @return array
 */
function mynotify_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata) {
  $token_service = \Drupal::token();
  $url_options = ['absolute' => TRUE];
  if (isset($options['langcode'])) {
    $url_options['language'] = \Drupal::languageManager()
      ->getLanguage($options['langcode']);
    $langcode = $options['langcode'];
  }
  else {
    $langcode = NULL;
  }
  $replacements = [];
  if ($type == 'mynotify' && !empty($data['mynotify'])) {
    /* @var \Drupal\mynotify\Entity\Mynotify $mynotify */
    $mynotify = $data['mynotify'];
    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'id':
          // In the case of hook mynotify_presave id is not set yet.
          $replacements[$original] = $mynotify->id() ?: t('not yet assigned');
          break;
        case 'name':
          $replacements[$original] = $mynotify->getName();
          break;
        case 'mail':
          $replacements[$original] = $mynotify->getMail();
          break;
        case 'phone':
          $replacements[$original] = $mynotify->getPhone();
          break;
        case 'text':
          $replacements[$original] = $mynotify->getText();
          break;
        case 'edit-url':
          $replacements[$original] = $mynotify->id() ? $mynotify->url('edit-form', $url_options) : t('not yet assigned');
          break;
        case 'created':
          $date_format = DateFormat::load('medium');
          $bubbleable_metadata->addCacheableDependency($date_format);
          // In the case of mynotify_presave the created date may not yet be set.
          $replacements[$original] = $mynotify->getCreatedTime() ? \Drupal::service('date.formatter')->format($mynotify->getCreatedTime(), 'medium', '', NULL, $langcode) : t('not yet created');
          break;
        case 'owner':

          $account = $mynotify->getOwner() ? $mynotify->getOwner() : User::load(0);
          $bubbleable_metadata->addCacheableDependency($account);
          $replacements[$original] = $account->label();
          break;
        case 'product':
          $product = $mynotify->getProduct() ? $mynotify->getProduct() : '';
          if ($product) {
            $replacements[$original] = $product->label();
          }
          else {
            $replacements[$original] = '';
          }
          break;
        case 'mails-not-notified':
          $srt = '';
          $product = $mynotify->getProduct() ? $mynotify->getProduct() : '';
          if ($product) {
            /* @var \Drupal\mynotify\MynotifyStorageInterface $mynotify_storage */
            $mynotify_storage = \Drupal::entityTypeManager()
              ->getStorage('mynotify');
            $mynotifies = $mynotify_storage->getMynotifiesNotNotifiedByProductId($product->id());
            if (!empty($mynotifies)) {
              $mails = [];
              foreach ($mynotifies as $mynotify) {
                $mail = $mynotify->getMail();
                $mails[$mail] = $mail;
              }
              $srt = implode(', ', $mails);
            }
          }
          $replacements[$original] = $srt;
          break;
      }

      if ($owner_tokens = $token_service->findWithPrefix($tokens, 'owner')) {
        $replacements += $token_service->generate('user', $owner_tokens, ['user' => $mynotify->getOwner()], $options, $bubbleable_metadata);
      }

      if ($product_tokens = $token_service->findWithPrefix($tokens, 'product')) {
        $replacements += $token_service->generate('commerce_product', $product_tokens, ['commerce_product' => $mynotify->getProduct()], $options, $bubbleable_metadata);
      }

    }


  }

  return $replacements;
}
