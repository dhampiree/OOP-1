#include <string>
#include <iostream>

using namespace std;

class Vigenier
{

private:
  string SECRET;
  int SECRET_CAPACITY;

  int shift (int idx) 
  {
    char c = this->SECRET[idx % this->SECRET_CAPACITY];
    int mod = (isupper(c)) ? 'A' : 'a';
    return c - mod;
  }


public:
  Vigenier(string secret)
  {
    this->SECRET = secret;
    this->SECRET_CAPACITY = secret.size();
  }

  void encode(string message) 
  {
    const int INPUT_LIMIT = message.size();
    char convertor = ' ';
    for (int i = 0; i < INPUT_LIMIT; i++) 
    {
      if (!isalpha(message[i])) 
      {
        cout << message[i];
        continue;
      }
      char base = (isupper(message[i])) ? 'A' : 'a';
      convertor = base + (message[i] + this->shift(i) - base) % 26;
      cout << convertor;
    }
  }

  ~Vigenier()
  {
  }
  
};

int main(int argc, string argv[])
{
  string  secret = "";
  string  message = "";

  cout << "Enter secret key: ";
  cin >> secret;
  Vigenier vig(secret);
  
  cout << "Enter private message: ";
  cin >> message;
  vig.encode(message);
  
  return 0;
}
