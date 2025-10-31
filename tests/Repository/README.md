# Repository Tests

这些 Repository 测试类目前使用单元测试（继承 `TestCase`）而不是集成测试（继承 `AbstractIntegrationTestCase`），
这是为了避免 Doctrine 实体映射中 `UserInterface` 依赖解析问题。

相关 Issue: https://github.com/tourze/php-monorepo/issues/814

一旦该 Issue 解决，这些测试应该重新改为集成测试。