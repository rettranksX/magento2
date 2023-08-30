namespace Dev\RestApi\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Dev\RestApi\Model\Api\ProductRepositoryInterface;

class Index extends Action
{
    protected $pageFactory;
    protected $productRepository;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->productRepository = $productRepository;
    }

    public function execute()
    {
        $details = 1;
        $productsData = $this->productRepository->getProducts($details);

        $result = $this->pageFactory->create();
        $result->getConfig()->getTitle()->set('My Custom Title');
        $result->getLayout()->getBlock('custom.block.name')->setData('productsData', $productsData);

        return $result;
    }
}
