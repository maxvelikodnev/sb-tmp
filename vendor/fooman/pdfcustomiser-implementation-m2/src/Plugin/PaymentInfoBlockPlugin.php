<?php
namespace Fooman\PdfCustomiser\Plugin;

/**
 * @author     Kristof Ringleff
 * @copyright  Copyright (c) 2009 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class PaymentInfoBlockPlugin
{
    private $resolver;

    public function __construct(
        \Magento\Framework\View\Element\Template\File\Resolver $resolver
    ) {
        $this->resolver = $resolver;
    }

    public function aroundGetTemplateFile(
        \Magento\Payment\Block\Info $subject,
        \Closure $proceed,
        $template = null
    ) {
        try {
            $storeId = $subject->getMethod()->getStore();
        } catch (\Exception $e) {
            $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        }
        $params = [
            'module' => $subject->getModuleName(),
            'store_id' => $storeId,
            'theme' => $subject->getFoomanThemePath()
        ];
        $area = $subject->getArea();
        if ($area) {
            $params['area'] = $area;
        }
        return $this->resolver->getTemplateFileName($template ?: $subject->getTemplate(), $params);
    }
}
