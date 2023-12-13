# Dựng và chạy thử Cron
- Để tạo một cronjob thực thi một vấn đề nào đó ta dùng
B1: Tạo folder theo đường dẫn `src/Command` sau đó tạo file bên trong ví dụ `src/Command/HelloCommand.php`.
B2: Viết logic xử lý vấn đề cần được xử lý vào bên trong hàm `execute` để nó hoạt động.
  ```php
  <?php
  namespace App\Command;
  
  use Cake\Command\Command;
  use Cake\Console\Arguments;
  use Cake\Console\ConsoleIo;
  
  class HelloCommand extends Command
  {
      public function execute(Arguments $args, ConsoleIo $io): int
      {
          $io->out('Hello world.');
  
          return static::CODE_SUCCESS;
      }
  }
  ```
B3: Dùng đâu lệnh sau để chạy.
  ```sh
  // bin/cake nameCommand
  // ex:
  bin/cake hello
  ```

# Một số điều đáng lưu ý
- `buildOptionParser` có thể hiều là hàm đễ truyền thêm tham số đầu vào gồm các hàm con:
  - `addArgument` xác định đối số truyền vào
    
    ```php
       protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
      {
          $parser->addArgument('name', [
              'help' => 'What is your name'
          ]);
          return $parser;
      }
    ```
  - `addOption` thêm bổ trợ lựa chọn cho đối tượng cần xử lý logic
    
     ```php
         // ...
        protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
        {
            $parser
                ->addArgument('name', [
                    'help' => 'What is your name'
                ])
                ->addOption('yell', [
                    'help' => 'Shout the name',
                    'short' => 'y',
                    'boolean' => true
                ]);
          return $parser;
      }
     ```

  - Ví dụ
    ```php
    <?php
    namespace App\Command;
    
    use Cake\Command\Command;
    use Cake\Console\Arguments;
    use Cake\Console\ConsoleIo;
    use Cake\Console\ConsoleOptionParser;
    
    class HelloCommand extends Command
    {
        protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
        {
            // chỉnh sửa thông tin hiện thị của option
            $parser->addArgument('name', [
                'help' => 'What is your name'  // ex: cake hello --help
            ]);
            return $parser;
        }
    
        public function execute(Arguments $args, ConsoleIo $io): int
        {
            $name = $args->getArgument('name');
            $io->out("Hello {$name}.");
    
            return static::CODE_SUCCESS;
        }
    }
    ```
  - Để thực thi đoạn ví dụ trên ra sẽ dùng thêm parser
    ```php
    bin/cake hello dev
    
    // Outputs
    // Hello dev
    ```
- Muốn thoát hoặc dừng thực thi `$io->abort()` muốn thông báo gì đó thì `$io->out()`
