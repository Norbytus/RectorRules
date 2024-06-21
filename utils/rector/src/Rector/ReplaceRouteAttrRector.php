<?php

declare(strict_types=1);

namespace Utils\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Utils\Rector\Tests\Rector\ReplaceRouteAttrRector\ReplaceRouteAttrRectorTest
 */
final class ReplaceRouteAttrRector extends AbstractRector implements ConfigurableRectorInterface
{
    private $fromTo = [];

    public function __construct(
        private RemoveAttrRector $removeAttrRector,
        private PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private PhpAttributeGroupFactory $phpAttributeGroupFactory,
    ) { }

    /**
     * @param array<class-string, class-string> $configuration 
     * @return void 
     */
    public function configure(array $configuration): void
    {
        $this->fromTo = $configuration;
        $this->removeAttrRector->configure(array_keys($this->fromTo));
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('// @todo fill the description', [
            new CodeSample(
                <<<'CODE_SAMPLE'
// @todo fill code before
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// @todo fill code after
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        // @todo select node type
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->fromTo as $from => $to) {
            if (!$this->phpAttributeAnalyzer->hasPhpAttribute($node, $from)) {
                continue;
            }

            $this->removeAttrRector->refactor($node);
            $attrGroup = $this->phpAttributeGroupFactory->createFromClass($to);
            $node->attrGroups[] = $attrGroup;
        }

        return $node;
    }
}
