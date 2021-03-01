library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

entity ALU is
    Port ( a        : in  std_logic_vector (11 downto 0);   -- Primer operando.
           b        : in  std_logic_vector (11 downto 0);   -- Segundo operando.
           sop      : in  std_logic_vector (2 downto 0);    -- Selector de la operaci�n.
           c        : out std_logic;                        -- Se�al de 'carry'.
           z        : out std_logic;                        -- Se�al de 'zero'.
           n        : out std_logic;                        -- Se�al de 'nagative'.
           o        : out std_logic;
           result   : out std_logic_vector (11 downto 0));  -- Resultado de la operaci�n.
end ALU;

architecture Behavioral of ALU is

signal out_adder       : std_logic_vector (11 downto 0);
signal out_sub         : std_logic_vector (11 downto 0);
signal out_and         : std_logic_vector (11 downto 0);
signal out_not         : std_logic_vector (11 downto 0);
signal out_shift_left  : std_logic_vector (11 downto 0);
signal out_shift_right : std_logic_vector (11 downto 0);
signal result_signal   : std_logic_vector (11 downto 0);

 component MuxALU
       Port ( 
           in0  : in std_logic_vector (11 downto 0);
           in1  : in std_logic_vector (11 downto 0);
           in2  : in std_logic_vector (11 downto 0);
           in3  : in std_logic_vector (11 downto 0);
           in4  : in std_logic_vector (11 downto 0);
           in5  : in std_logic_vector (11 downto 0);
           sel  : in std_logic_vector (2 downto 0);
           out_mux  : out std_logic_vector (11 downto 0));
    end component;
 
 component ShiftLeft
      Port ( 
           in0  : in std_logic_vector (11 downto 0);
           out_shift  : out std_logic_vector (11 downto 0));
      end component;
      
 component ShiftRight
      Port ( 
           in0  : in std_logic_vector (11 downto 0);
           out_shift  : out std_logic_vector (11 downto 0));
      end component;
      
 component AdderSubstractor
    Port ( a : in std_logic_vector (11 downto 0);
           b : in std_logic_vector (11 downto 0);
           e : in STD_LOGIC;
           c : out STD_LOGIC;
           s : out std_logic_vector (11 downto 0));
    end component;
    
    
  component ZeroDetector is
    Port ( in0  : in std_logic_vector (11 downto 0);
           out_zero  : out std_logic);
    end component;
    
  component OverflowDetector is
    Port ( a : in std_logic_vector (11 downto 0);
           b : in std_logic_vector (11 downto 0);
           result : in std_logic_vector (11 downto 0);
           overflow_out : out STD_LOGIC);
    end component;
    
begin

out_and <= a and b;
out_not <= not a;
n <= result_signal(11);
result <= result_signal;

inst_MuxALU: MuxALU port map (
    in0 => out_adder,
    in1 => out_sub,
    in2 => out_and,
    in3 => out_not,
    in4 => out_shift_left,
    in5 => out_shift_right,
    sel => sop,
    out_mux => result_signal
    );

inst_Adder: AdderSubstractor port map (
   a => a,
   b => b,
   e => '0',
   c => c,
   s => out_adder
   );

inst_Sub: AdderSubstractor port map (
   a => a,
   b => b,
   e => '1',
   s => out_sub
   );
   
inst_ShiftLeft: ShiftLeft port map (
   in0 => a,
   out_shift => out_shift_left
   );
   
inst_ShiftRight: ShiftRight port map (
   in0 => a,
   out_shift => out_shift_right
   );
   
inst_ZeroDetector: ZeroDetector port map (
   in0 => result_signal,
   out_zero => z
   );
  
inst_OverflowDetector: OverflowDetector port map (
   a => a,
   b => b,
   result => result_signal,
   overflow_out => o
   );
    
end Behavioral;
