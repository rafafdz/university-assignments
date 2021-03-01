library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

entity OverflowDetector is
    Port ( a : in std_logic_vector (11 downto 0);
           b : in std_logic_vector (11 downto 0);
           result : in std_logic_vector (11 downto 0);
           overflow_out : out STD_LOGIC);
end OverflowDetector;

architecture Behavioral of OverflowDetector is
   
 
signal xor0_out : STD_LOGIC;
signal xor0_not : STD_LOGIC;
signal xor1_out : STD_LOGIC;


begin

xor0_out <= a(11) xor b(11);
xor0_not <= not xor0_out;

xor1_out <= result(11) xor a(11);

overflow_out <= xor0_not and xor1_out;

end Behavioral;
